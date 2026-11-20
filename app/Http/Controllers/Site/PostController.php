<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Unit;
use Illuminate\Http\Request;

/** Berita, Kegiatan, Pengumuman, Edukasi — daftar & detail. */
class PostController extends Controller
{
    public function index(Request $request, string $type)
    {
        $base = Post::published()->ofType($type);

        $posts = (clone $base)->with(['category', 'unit'])
            ->when($request->filled('kategori'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $request->kategori)))
            ->when($request->filled('unit'), fn ($q) => $q->whereHas('unit', fn ($u) => $u->where('slug', $request->unit)))
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->latestPublished()
            ->paginate(9)->withQueryString();

        return view('site.posts.index', [
            'type' => $type,
            'posts' => $posts,
            'categories' => Category::whereIn('id', (clone $base)->select('category_id'))->orderBy('name')->get(),
            'units' => Unit::whereIn('id', (clone $base)->select('unit_id'))->ordered()->get(),
        ]);
    }

    public function show(Request $request, string $type, string $slug)
    {
        $post = Post::published()->ofType($type)->where('slug', $slug)
            ->with(['images', 'unit', 'category', 'author'])->firstOrFail();

        // Hitung kunjungan sekali per sesi.
        $seen = $request->session()->get('viewed_posts', []);
        if (! in_array($post->id, $seen, true)) {
            $post->increment('views');
            $request->session()->push('viewed_posts', $post->id);
        }

        $related = Post::published()->ofType($type)->whereKeyNot($post->id)
            ->when($post->category_id, fn ($q) => $q->orderByRaw('category_id = ? DESC', [$post->category_id]))
            ->latestPublished()->with(['category', 'unit'])->limit(3)->get();

        return view('site.posts.show', compact('type', 'post', 'related'));
    }
}
