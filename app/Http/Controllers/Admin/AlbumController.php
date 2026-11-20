<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AlbumRequest;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Unit;
use App\Services\ContentWorkflow;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlbumController extends Controller
{
    public function __construct(private ImageService $images, private ContentWorkflow $workflow)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Album::class);
        $user = $request->user();

        $albums = Album::ownedBy($user)->with(['unit', 'cover'])->withCount('photos')
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('unit_id') && $user->isSuperAdmin(), fn ($q) => $q->where('unit_id', $request->unit_id))
            ->orderByDesc('updated_at')
            ->paginate(15)->withQueryString();

        return view('admin.albums.index', [
            'albums' => $albums,
            'units' => $user->isSuperAdmin() ? Unit::ordered()->get() : collect(),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Album::class);

        return view('admin.albums.form', $this->formData(new Album(['unit_id' => $request->user()->unit_id, 'tanggal' => now()])));
    }

    public function store(AlbumRequest $request)
    {
        $user = $request->user();

        $album = DB::transaction(function () use ($request, $user) {
            $album = new Album($request->safe()->only(['judul', 'deskripsi', 'tanggal']));
            $album->user_id = $user->id;
            $album->unit_id = $user->isSuperAdmin() ? $request->validated('unit_id') : $user->unit_id;
            $album->forceFill($this->workflow->statusFor($user, $request->validated('action')));
            $album->save();
            $this->storePhotos($request, $album);

            return $album;
        });

        return redirect()->route('admin.albums.edit', $album)->with('success', $this->message($album));
    }

    public function edit(Album $album)
    {
        $this->authorize('update', $album);
        $album->load('photos');

        return view('admin.albums.form', $this->formData($album));
    }

    public function update(AlbumRequest $request, Album $album)
    {
        $user = $request->user();

        DB::transaction(function () use ($request, $album, $user) {
            $album->fill($request->safe()->only(['judul', 'deskripsi', 'tanggal']));
            if ($user->isSuperAdmin()) {
                $album->unit_id = $request->validated('unit_id');
            }
            $album->forceFill($this->workflow->statusFor($user, $request->validated('action'), $album));
            $album->save();

            foreach ($album->photos as $photo) {
                if ($request->has("captions.{$photo->id}")) {
                    $photo->update(['caption' => $request->input("captions.{$photo->id}")]);
                }
            }
            $this->storePhotos($request, $album);
        });

        return redirect()->route('admin.albums.edit', $album)->with('success', $this->message($album));
    }

    public function destroy(Album $album)
    {
        $this->authorize('delete', $album);
        foreach ($album->photos as $photo) {
            $this->images->delete($photo->path);
        }
        $album->delete();

        return redirect()->route('admin.albums.index')->with('success', 'Album berhasil dihapus.');
    }

    /** Hapus satu foto dari album. */
    public function destroyPhoto(Album $album, Photo $photo)
    {
        abort_unless((int) $photo->album_id === (int) $album->id, 404);
        $this->authorize('update', $album);
        $this->images->delete($photo->path);
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    private function storePhotos(AlbumRequest $request, Album $album): void
    {
        $order = (int) $album->photos()->max('urutan');
        foreach ($request->file('photos', []) as $file) {
            $album->photos()->create([
                'path' => $this->images->store($file, 'albums/'.$album->id),
                'urutan' => ++$order,
            ]);
        }
    }

    private function formData(Album $album): array
    {
        return [
            'album' => $album,
            'units' => Unit::ordered()->get()->mapWithKeys(fn ($u) => [$u->id => $u->name])->all(),
            'canPublish' => ContentWorkflow::canPublishDirectly(auth()->user()),
        ];
    }

    private function message(Album $album): string
    {
        return match ($album->status) {
            'published' => 'Album berhasil disimpan dan sudah tayang.',
            'pending' => 'Album disimpan dan dikirim untuk persetujuan Super Admin.',
            default => 'Album disimpan sebagai draft.',
        };
    }
}
