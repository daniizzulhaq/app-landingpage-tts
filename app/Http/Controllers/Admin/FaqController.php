<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;
use App\Support\Html;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::query()
            ->when($request->filled('q'), fn ($q) => $q->where('pertanyaan', 'like', '%'.$request->q.'%'))
            ->ordered()
            ->paginate(20)->withQueryString();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.form', ['faq' => new Faq]);
    }

    public function store(FaqRequest $request)
    {
        Faq::create($this->payload($request));

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.form', compact('faq'));
    }

    public function update(FaqRequest $request, Faq $faq)
    {
        $faq->update($this->payload($request));

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    private function payload(FaqRequest $request): array
    {
        $data = $request->validated();
        $data['urutan'] = (int) ($data['urutan'] ?? 0);
        $data['jawaban'] = Html::clean($data['jawaban']) ?? '';

        return $data;
    }
}
