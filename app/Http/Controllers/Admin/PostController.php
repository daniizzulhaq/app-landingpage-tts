<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Unit;
use App\Services\ContentWorkflow;
use App\Services\ImageService;
use App\Support\Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * CRUD konten Berita, Kegiatan, Pengumuman, Edukasi (satu tabel "posts", dibedakan "type").
 */
class PostController extends Controller
{
    public function __construct(private ImageService $images, private ContentWorkflow $workflow)
    {
    }

    public function index(Request $request, string $type)
    {
        $user = $request->user();
        abort_if($type === Post::TYPE_PENGUMUMAN && ! $user->isSuperAdmin(), 403);
        $this->authorize('viewAny', Post::class);

        $posts = Post::ofType($type)->ownedBy($user)
            ->with(['unit', 'category', 'author'])
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('unit_id') && $user->isSuperAdmin(), fn ($q) => $q->where('unit_id', $request->unit_id))
            ->orderByDesc('updated_at')
            ->paginate(15)->withQueryString();

        return view('admin.posts.index', [
            'type' => $type,
            'posts' => $posts,
            'categories' => Category::orderBy('name')->get(),
            'units' => $user->isSuperAdmin() ? Unit::ordered()->get() : collect(),
        ]);
    }

    public function create(Request $request, string $type)
    {
        $this->authorize('createType', [Post::class, $type]);

        $post = new Post(['type' => $type, 'unit_id' => $request->user()->unit_id]);

        return view('admin.posts.form', $this->formData($type, $post));
    }

    public function store(PostRequest $request, string $type)
    {
        $user = $request->user();

        $post = DB::transaction(function () use ($request, $type, $user) {
            $post = new Post($this->payload($request, $type));
            $post->type = $type;
            $post->user_id = $user->id;
            $post->unit_id = $user->isSuperAdmin() ? $request->validated('unit_id') : $user->unit_id;
            $post->forceFill($this->workflow->statusFor($user, $request->validated('action')));
            if ($user->isSuperAdmin() && $request->filled('published_at') && $post->status === 'published') {
                $post->published_at = $request->date('published_at');
            }
            $post->thumbnail = $request->hasFile('thumbnail') ? $this->images->store($request->file('thumbnail'), 'posts') : null;
            $post->lampiran = $request->hasFile('lampiran') ? $this->images->store($request->file('lampiran'), 'posts/lampiran') : null;
            $post->save();
            $this->saveGallery($request, $post);

            return $post;
        });

        return redirect()->route('admin.posts.index', $type)->with('success', $this->message($post, 'disimpan'));
    }

    public function show(string $type, Post $post)
    {
        abort_unless($post->type === $type, 404);
        $this->authorize('view', $post);
        $post->load(['images', 'unit', 'category', 'author', 'approver']);

        return view('admin.posts.show', compact('type', 'post'));
    }

    public function edit(string $type, Post $post)
    {
        abort_unless($post->type === $type, 404);
        $this->authorize('update', $post);
        $post->load('images');

        return view('admin.posts.form', $this->formData($type, $post));
    }

    public function update(PostRequest $request, string $type, Post $post)
    {
        $user = $request->user();

        DB::transaction(function () use ($request, $post, $user) {
            $post->fill($this->payload($request, $post->type));
            if ($user->isSuperAdmin()) {
                $post->unit_id = $request->validated('unit_id');
            }
            $post->forceFill($this->workflow->statusFor($user, $request->validated('action'), $post));
            if ($user->isSuperAdmin() && $request->filled('published_at') && $post->status === 'published') {
                $post->published_at = $request->date('published_at');
            }

            if ($request->boolean('hapus_thumbnail')) {
                $this->images->delete($post->thumbnail);
                $post->thumbnail = null;
            }
            $post->thumbnail = $this->images->replace($request->file('thumbnail'), $post->thumbnail, 'posts');

            if ($request->boolean('hapus_lampiran')) {
                $this->images->delete($post->lampiran);
                $post->lampiran = null;
            }
            $post->lampiran = $this->images->replace($request->file('lampiran'), $post->lampiran, 'posts/lampiran');
            $post->save();

            // Perbarui caption & hapus foto galeri yang dipilih
            foreach ($post->images as $image) {
                if (in_array($image->id, $request->input('hapus_images', []))) {
                    $this->images->delete($image->path);
                    $image->delete();
                } elseif ($request->has("captions.{$image->id}")) {
                    $image->update(['caption' => $request->input("captions.{$image->id}")]);
                }
            }
            $this->saveGallery($request, $post);
        });

        return redirect()->route('admin.posts.index', $type)->with('success', $this->message($post, 'diperbarui'));
    }

    public function destroy(string $type, Post $post)
    {
        abort_unless($post->type === $type, 404);
        $this->authorize('delete', $post);

        foreach ($post->images as $image) {
            $this->images->delete($image->path);
        }
        $this->images->delete($post->thumbnail);
        $this->images->delete($post->lampiran);
        $post->delete();

        return back()->with('success', Post::typeLabel($type).' berhasil dihapus.');
    }

    private function payload(PostRequest $request, string $type): array
    {
        $data = $request->safe()->only(['judul', 'ringkasan', 'isi', 'category_id', 'lokasi', 'tanggal_kegiatan']);
        $data['isi'] = Html::clean($data['isi'] ?? null);
        // Penanda "penting" (tampil di strip pengumuman beranda) hanya untuk Super Admin.
        $data['is_penting'] = $request->user()->isSuperAdmin() && $request->boolean('is_penting');

        return $data;
    }

    private function saveGallery(PostRequest $request, Post $post): void
    {
        foreach ($request->file('images', []) as $file) {
            $post->images()->create(['path' => $this->images->store($file, 'posts/galeri')]);
        }
    }

    private function formData(string $type, Post $post): array
    {
        return [
            'type' => $type,
            'post' => $post,
            'categories' => Category::orderBy('name')->pluck('name', 'id')->all(),
            'units' => Unit::ordered()->get()->mapWithKeys(fn ($u) => [$u->id => $u->name])->all(),
            'canPublish' => ContentWorkflow::canPublishDirectly(auth()->user()),
        ];
    }

    private function message(Post $post, string $verb): string
    {
        $label = Post::typeLabel($post->type);

        return match ($post->status) {
            'published' => "$label berhasil $verb dan sudah tayang.",
            'pending' => "$label berhasil $verb dan dikirim untuk persetujuan Super Admin.",
            default => "$label berhasil $verb sebagai draft.",
        };
    }
}
