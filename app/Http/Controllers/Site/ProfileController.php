<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Official;
use App\Models\Page;
use App\Models\Post;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /** Profil Polres: sejarah, visi misi, tugas & fungsi, wilayah hukum. */
    public function profile()
    {
        $keys = ['sejarah', 'visi_misi', 'tugas_fungsi', 'wilayah_hukum'];
        $pages = Page::whereIn('key', $keys)->get()->keyBy('key');

        return view('site.profile.index', [
            'sections' => collect($keys)->map(fn ($k) => $pages[$k] ?? new Page(['key' => $k, 'judul' => Page::KEYS[$k]])),
        ]);
    }

    public function leaders()
    {
        return view('site.profile.leaders', [
            'kapolres' => Official::kapolresForDisplay(),
            'wakapolres' => Official::where('level', 'wakapolres')->orderBy('urutan')->first(),
            'sambutan' => Page::sambutanKapolresForDisplay(),
            'pejabat' => Official::with('unit')->where('level', 'pejabat')
                ->whereHas('unit', fn ($q) => $q->where('type', '!=', 'polsek'))
                ->get()->sortBy(fn ($o) => [array_search($o->unit->type, array_keys(Unit::TYPES)), $o->unit->urutan, $o->urutan])->values(),
        ]);
    }

    /** Bagan otomatis dari data pejabat + unit. */
    public function structure()
    {
        $units = Unit::with(['officials' => fn ($q) => $q->where('level', 'pejabat')])->ordered()->get()->groupBy('type');

        return view('site.profile.structure', [
            'kapolres' => Official::kapolresForDisplay(),
            'wakapolres' => Official::where('level', 'wakapolres')->orderBy('urutan')->first(),
            'groups' => collect(Unit::TYPES)->map(fn ($label, $type) => ['label' => $label, 'units' => $units[$type] ?? collect()]),
            'bagan' => setting('bagan_struktur'),
        ]);
    }

    public function units()
    {
        $units = Unit::whereIn('type', ['bag', 'sat', 'sie'])->ordered()->get()->groupBy('type');

        return view('site.profile.units', [
            'groups' => collect(['bag', 'sat', 'sie'])->mapWithKeys(fn ($t) => [$t => $units[$t] ?? collect()]),
        ]);
    }

    public function unit(Request $request, Unit $unit)
    {
        $unit->load('officials');

        return view('site.profile.unit', [
            'unit' => $unit,
            'kegiatan' => Post::published()->where('unit_id', $unit->id)->whereIn('type', [Post::TYPE_KEGIATAN, Post::TYPE_BERITA])
                ->latestPublished()->paginate(6)->withQueryString(),
        ]);
    }

    public function polsek(Request $request)
    {
        $q = trim((string) $request->input('q'));

        return view('site.profile.polsek', [
            'polseks' => Unit::where('type', 'polsek')
                ->when($q !== '', fn ($query) => $query->where('name', 'like', '%'.$q.'%'))
                ->with(['officials' => fn ($o) => $o->orderBy('urutan')])
                ->ordered()->get(),
            'q' => $q,
        ]);
    }
}
