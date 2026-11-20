<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Request;

/** Pencarian ke posts, services, dan documents; hasil dikelompokkan per jenis. */
class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim(mb_substr((string) $request->input('q'), 0, 100));
        $results = collect();

        if (mb_strlen($q) >= 2) {
            $like = '%'.addcslashes($q, '%_\\').'%';

            $posts = Post::published()->with('category')
                ->where(fn ($w) => $w->where('judul', 'like', $like)->orWhere('ringkasan', 'like', $like)->orWhere('isi', 'like', $like))
                ->latestPublished()->limit(40)->get()->groupBy('type');

            foreach (Post::TYPES as $type => [, $label, $icon]) {
                if (isset($posts[$type])) {
                    $results->push([
                        'label' => $label, 'icon' => $icon,
                        'items' => $posts[$type]->map(fn ($p) => [
                            'title' => $p->judul, 'url' => $p->url(), 'text' => $p->excerpt(180),
                            'meta' => tgl($p->displayDate()),
                        ]),
                    ]);
                }
            }

            $services = Service::where(fn ($w) => $w->where('nama', 'like', $like)->orWhere('deskripsi', 'like', $like)
                ->orWhere('persyaratan', 'like', $like)->orWhere('prosedur', 'like', $like))->ordered()->limit(10)->get();
            if ($services->isNotEmpty()) {
                $results->push(['label' => 'Layanan', 'icon' => 'bi-card-checklist', 'items' => $services->map(fn ($s) => [
                    'title' => $s->nama, 'url' => route('site.services.show', $s->slug), 'text' => $s->deskripsi, 'meta' => 'Informasi layanan',
                ])]);
            }

            $documents = Document::where(fn ($w) => $w->where('judul', 'like', $like)->orWhere('deskripsi', 'like', $like))
                ->orderByDesc('tahun')->limit(10)->get();
            if ($documents->isNotEmpty()) {
                $results->push(['label' => 'Dokumen PPID', 'icon' => 'bi-folder2-open', 'items' => $documents->map(fn ($d) => [
                    'title' => $d->judul, 'url' => media_url($d->path), 'text' => $d->deskripsi,
                    'meta' => $d->kategoriLabel().($d->tahun ? ' · '.$d->tahun : '').' · '.$d->extension(), 'download' => true,
                ])]);
            }
        }

        return view('site.search', [
            'q' => $q,
            'results' => $results,
            'total' => $results->sum(fn ($g) => $g['items']->count()),
        ]);
    }
}
