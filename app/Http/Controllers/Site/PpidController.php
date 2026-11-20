<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\InformationRequestRequest;
use App\Models\Document;
use App\Models\InformationRequest;
use Illuminate\Http\Request;

class PpidController extends Controller
{
    public function index(Request $request)
    {
        $documents = Document::query()
            ->when($request->filled('tahun'), fn ($q) => $q->where('tahun', $request->tahun))
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->orderByDesc('tahun')->orderBy('judul')->get()->groupBy('kategori');

        return view('site.ppid', [
            'groups' => collect(Document::CATEGORIES)->map(fn ($label, $key) => ['label' => $label, 'items' => $documents[$key] ?? collect()]),
            'years' => Document::whereNotNull('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun'),
        ]);
    }

    public function store(InformationRequestRequest $request)
    {
        // Honeypot: bot biasanya mengisi field tersembunyi "website". Pura-pura sukses.
        if ($request->filled('website')) {
            return redirect()->to(route('site.ppid').'#permohonan')->with('ppid_success', true);
        }

        InformationRequest::create($request->safe()->only(['nama', 'kontak', 'informasi_diminta', 'tujuan']) + [
            'status' => 'baru',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->to(route('site.ppid').'#permohonan')->with('ppid_success', true);
    }
}
