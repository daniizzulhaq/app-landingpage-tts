<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderRequest;
use App\Models\Slider;
use App\Services\ImageService;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request)
    {
        $sliders = Slider::query()
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'aktif'))
            ->orderBy('urutan')->orderByDesc('id')
            ->paginate(20)->withQueryString();

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.form', ['slider' => new Slider(['is_active' => true])]);
    }

    public function store(SliderRequest $request)
    {
        $data = $this->payload($request);
        $data['gambar'] = $this->images->store($request->file('gambar'), 'sliders');
        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil ditambahkan.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.form', compact('slider'));
    }

    public function update(SliderRequest $request, Slider $slider)
    {
        $data = $this->payload($request);
        $data['gambar'] = $this->images->replace($request->file('gambar'), $slider->gambar, 'sliders');
        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil diperbarui.');
    }

    public function destroy(Slider $slider)
    {
        $this->images->delete($slider->gambar);
        $slider->delete();

        return back()->with('success', 'Slider berhasil dihapus.');
    }

    private function payload(SliderRequest $request): array
    {
        return [
            'judul' => $request->validated('judul'),
            'subjudul' => $request->validated('subjudul'),
            'link' => $request->validated('link'),
            'urutan' => (int) $request->validated('urutan'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
