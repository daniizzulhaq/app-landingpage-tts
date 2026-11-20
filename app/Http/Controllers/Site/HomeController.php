<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Album;
use App\Models\Official;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Video;

class HomeController extends Controller
{
    public function __invoke()
    {
        $posts = fn (string $type, int $limit) => Post::published()->ofType($type)
            ->with(['category', 'unit'])->latestPublished()->limit($limit)->get();

        return view('site.home', [
            'sliders' => Slider::active()->get(),
            'announcements' => Post::published()->ofType(Post::TYPE_PENGUMUMAN)->where('is_penting', true)->latestPublished()->limit(5)->get(),
            'services' => Service::ordered()->limit(4)->get(),
            'kapolres' => Official::where('level', 'kapolres')->orderBy('urutan')->first(),
            'sambutan' => Page::byKey('sambutan_kapolres'),
            'berita' => $posts(Post::TYPE_BERITA, 5),
            'kegiatan' => $posts(Post::TYPE_KEGIATAN, 4),
            'agendas' => Agenda::published()->upcoming()->limit(4)->get(),
            'albums' => Album::published()->with('cover')->withCount('photos')->has('photos')->latest('tanggal')->limit(5)->get(),
            'videos' => Video::latest('tanggal')->limit(2)->get(),
            'edukasi' => $posts(Post::TYPE_EDUKASI, 3),
        ]);
    }
}
