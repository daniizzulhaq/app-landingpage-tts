<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Video;

class GalleryController extends Controller
{
    public function photos()
    {
        return view('site.gallery.photos', [
            'albums' => Album::published()->with('cover')->withCount('photos')->has('photos')
                ->orderByDesc('tanggal')->orderByDesc('id')->paginate(12),
        ]);
    }

    public function album(string $slug)
    {
        $album = Album::published()->where('slug', $slug)->with(['photos', 'unit'])->firstOrFail();

        return view('site.gallery.album', compact('album'));
    }

    public function videos()
    {
        return view('site.gallery.videos', [
            'videos' => Video::orderByDesc('tanggal')->orderByDesc('id')->paginate(12),
        ]);
    }
}
