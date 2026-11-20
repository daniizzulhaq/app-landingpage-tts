<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DocumentRequest;
use App\Models\Document;
use App\Services\ImageService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(private ImageService $images)
    {
    }

    public function index(Request $request)
    {
        $documents = Document::query()
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori', $request->kategori))
            ->when($request->filled('tahun'), fn ($q) => $q->where('tahun', $request->tahun))
            ->orderByDesc('tahun')->orderByDesc('id')
            ->paginate(20)->withQueryString();

        return view('admin.documents.index', [
            'documents' => $documents,
            'years' => Document::whereNotNull('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun'),
        ]);
    }

    public function create()
    {
        return view('admin.documents.form', ['document' => new Document(['kategori' => 'berkala', 'tahun' => now()->year])]);
    }

    public function store(DocumentRequest $request)
    {
        $data = $request->safe()->except('file');
        $data['path'] = $this->images->store($request->file('file'), 'documents');
        Document::create($data);

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(Document $document)
    {
        return view('admin.documents.form', compact('document'));
    }

    public function update(DocumentRequest $request, Document $document)
    {
        $data = $request->safe()->except('file');
        $data['path'] = $this->images->replace($request->file('file'), $document->path, 'documents');
        $document->update($data);

        return redirect()->route('admin.documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        $this->images->delete($document->path);
        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
