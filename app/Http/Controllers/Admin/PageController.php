<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Services\ImageService;
use App\Support\Html;

class PageController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index()
    {
        // Pastikan semua halaman statis yang wajib tersedia.
        foreach (Page::KEYS as $key => $judul) {
            Page::firstOrCreate(['key' => $key], ['judul' => $judul]);
        }

        return view('admin.pages.index', ['pages' => Page::orderByRaw("FIELD(`key`, '".implode("','", array_keys(Page::KEYS))."')")->get()]);
    }

    public function edit(Page $page)
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {
        $data = ['judul' => $request->validated('judul'), 'isi' => Html::clean($request->validated('isi'))];
        if ($request->boolean('hapus_gambar')) {
            $this->images->delete($page->gambar);
            $data['gambar'] = null;
        }
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->images->replace($request->file('gambar'), $page->gambar, 'pages');
        }
        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman "'.$page->judul.'" berhasil diperbarui.');
    }
}
