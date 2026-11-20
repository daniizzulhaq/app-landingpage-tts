<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UnitRequest;
use App\Models\Unit;
use App\Services\ImageService;
use App\Support\Html;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Unit::class);
        $user = $request->user();

        $units = Unit::query()
            ->when(! $user->isSuperAdmin(), fn ($q) => $q->whereKey($user->unit_id))
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->ordered()
            ->paginate(20)->withQueryString();

        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $this->authorize('create', Unit::class);

        return view('admin.units.form', ['unit' => new Unit(['type' => 'sat'])]);
    }

    public function store(UnitRequest $request)
    {
        $data = $this->payload($request);
        $data['foto'] = $request->hasFile('foto') ? $this->images->store($request->file('foto'), 'units') : null;
        Unit::create($data);

        return redirect()->route('admin.units.index')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        $this->authorize('update', $unit);

        return view('admin.units.form', compact('unit'));
    }

    public function update(UnitRequest $request, Unit $unit)
    {
        $data = $this->payload($request);
        if ($request->boolean('hapus_foto')) {
            $this->images->delete($unit->foto);
            $data['foto'] = null;
        }
        if ($request->hasFile('foto')) {
            $data['foto'] = $this->images->replace($request->file('foto'), $unit->foto, 'units');
        }
        $unit->update($data);

        return redirect()->route('admin.units.index')->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        $this->authorize('delete', $unit);
        $this->images->delete($unit->foto);
        $unit->delete();

        return back()->with('success', 'Unit berhasil dihapus.');
    }

    private function payload(UnitRequest $request): array
    {
        $data = collect($request->validated())->except(['foto', 'hapus_foto'])->all();
        $data['urutan'] = $data['urutan'] ?? 0;
        if (! $request->user()->isSuperAdmin()) {
            unset($data['urutan']);
        }

        return Html::cleanFields($data, ['tugas_pokok', 'layanan_unit']);
    }
}
