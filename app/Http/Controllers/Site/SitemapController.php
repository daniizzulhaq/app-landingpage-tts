<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Post;
use App\Models\Service;
use App\Models\Unit;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /** sitemap.xml otomatis (di-cache 1 jam). */
    public function sitemap()
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = collect();
            $add = fn (string $loc, $lastmod = null, string $freq = 'weekly', string $priority = '0.6') => $urls->push(compact('loc', 'lastmod', 'freq', 'priority'));

            $add(route('home'), now(), 'daily', '1.0');
            foreach (['site.profile', 'site.leaders', 'site.structure', 'site.units.index', 'site.polsek',
                'site.services.index', 'site.complaints', 'site.faq', 'site.agenda',
                'site.gallery.photos', 'site.gallery.videos', 'site.ppid', 'site.contact'] as $name) {
                $add(route($name), null, 'monthly', '0.7');
            }
            foreach (array_keys(Post::TYPES) as $type) {
                $add(route('site.posts.index', $type), null, 'daily', '0.8');
            }
            Post::published()->select(['type', 'slug', 'updated_at'])->latest('published_at')->limit(5000)->get()
                ->each(fn ($p) => $add($p->url(), $p->updated_at, 'monthly', '0.6'));
            Service::select(['slug', 'updated_at'])->get()->each(fn ($s) => $add(route('site.services.show', $s->slug), $s->updated_at, 'monthly', '0.8'));
            Unit::select(['slug', 'updated_at'])->get()->each(fn ($u) => $add(route('site.units.show', $u->slug), $u->updated_at, 'monthly', '0.5'));
            Album::published()->select(['slug', 'updated_at'])->get()->each(fn ($a) => $add(route('site.gallery.album', $a->slug), $a->updated_at, 'monthly', '0.4'));

            return view('site.sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $lines = app()->environment('production')
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /cari', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /']; // situs non-produksi tidak diindeks

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
