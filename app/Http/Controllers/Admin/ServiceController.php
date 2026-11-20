<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Models\Unit;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request)
    {
        $services = Service::with('unit')->withCount('files')
            ->when($request->filled('q'), fn ($q) => $q->where('nama', 'like', '%'.$request->q.'%'))
            ->when($request->filled('unit_id'), fn ($q) => $q->where('unit_id', $request->unit_id))
            ->ordered()
            ->paginate(20)->withQueryString();

        return view('admin.services.index', ['services' => $services, 'units' => Unit::ordered()->get()]);
    }

    public function create()
    {
        return view('admin.services.form', ['service' => new Service(['ikon' => 'bi-card-checklist']), 'units' => $this->units()]);
    }

    public function store(ServiceRequest $request)
    {
        DB::transaction(function () use ($request) {
            $service = Service::create($this->payload($request));
            $this->storeFiles($request, $service);
        });

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        $service->load('files');

        return view('admin.services.form', ['service' => $service, 'units' => $this->units()]);
    }

    public function update(ServiceRequest $request, Service $service)
    {
        DB::transaction(function () use ($request, $service) {
            $service->update($this->payload($request));
            foreach ($service->files as $file) {
                if (in_array($file->id, $request->input('hapus_files', []))) {
                    $this->images->delete($file->path);
                    $file->delete();
                }
            }
            $this->storeFiles($request, $service);
        });

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        foreach ($service->files as $file) {
            $this->images->delete($file->path);
        }
        $service->delete();

        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    private function payload(ServiceRequest $request): array
    {
        $data = collect($request->validated())->except(['files', 'file_names', 'hapus_files'])->all();
        $data['urutan'] = (int) ($data['urutan'] ?? 0);

        return $data;
    }

    private function storeFiles(ServiceRequest $request, Service $service): void
    {
        $names = $request->input('file_names', []);
        foreach ($request->file('files', []) as $i => $file) {
            $service->files()->create([
                'nama' => $names[$i] ?? null ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'path' => $this->images->store($file, 'services'),
            ]);
        }
    }

    private function units(): array
    {
        return Unit::ordered()->get()->mapWithKeys(fn ($u) => [$u->id => $u->name])->all();
    }
}
