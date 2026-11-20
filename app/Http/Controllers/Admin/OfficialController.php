<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OfficialRequest;
use App\Models\Official;
use App\Models\Unit;
use App\Services\ImageService;
use App\Support\Html;
use Illuminate\Http\Request;

class OfficialController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request)
    {
        $officials = Official::with('unit')
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('nama', 'like', '%'.$request->q.'%')->orWhere('jabatan', 'like', '%'.$request->q.'%')))
            ->when($request->filled('level'), fn ($q) => $q->where('level', $request->level))
            ->when($request->filled('unit_id'), fn ($q) => $q->where('unit_id', $request->unit_id))
            ->orderByRaw("FIELD(level, 'kapolres', 'wakapolres', 'pejabat')")
            ->orderBy('urutan')
            ->paginate(20)->withQueryString();

        return view('admin.officials.index', ['officials' => $officials, 'units' => Unit::ordered()->get()]);
    }

    public function create()
    {
        return view('admin.officials.form', ['official' => new Official(['level' => 'pejabat']), 'units' => Unit::ordered()->get()]);
    }

    public function store(OfficialRequest $request)
    {
        $data = $this->payload($request);
        $data['foto'] = $request->hasFile('foto') ? $this->images->store($request->file('foto'), 'officials') : null;
        Official::create($data);

        return redirect()->route('admin.officials.index')->with('success', 'Data pejabat berhasil ditambahkan.');
    }

    public function edit(Official $official)
    {
        return view('admin.officials.form', ['official' => $official, 'units' => Unit::ordered()->get()]);
    }

    public function update(OfficialRequest $request, Official $official)
    {
        $data = $this->payload($request);
        if ($request->boolean('hapus_foto')) {
            $this->images->delete($official->foto);
            $data['foto'] = null;
        }
        if ($request->hasFile('foto')) {
            $data['foto'] = $this->images->replace($request->file('foto'), $official->foto, 'officials');
        }
        $official->update($data);

        return redirect()->route('admin.officials.index')->with('success', 'Data pejabat berhasil diperbarui.');
    }

    public function destroy(Official $official)
    {
        $this->images->delete($official->foto);
        $official->delete();

        return back()->with('success', 'Data pejabat berhasil dihapus.');
    }

    private function payload(OfficialRequest $request): array
    {
        $data = collect($request->validated())->except(['foto', 'hapus_foto'])->all();
        $data['urutan'] = $data['urutan'] ?? 0;

        return Html::cleanFields($data, ['tugas']);
    }
}
