<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InformationRequestUpdateRequest;
use App\Models\InformationRequest;
use Illuminate\Http\Request;

class InformationRequestController extends Controller
{
    public function index(Request $request)
    {
        $items = InformationRequest::query()
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('nama', 'like', '%'.$request->q.'%')
                ->orWhere('kontak', 'like', '%'.$request->q.'%')->orWhere('informasi_diminta', 'like', '%'.$request->q.'%')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)->withQueryString();

        return view('admin.information-requests.index', compact('items'));
    }

    public function show(InformationRequest $informationRequest)
    {
        return view('admin.information-requests.show', ['item' => $informationRequest]);
    }

    public function update(InformationRequestUpdateRequest $request, InformationRequest $informationRequest)
    {
        $informationRequest->update($request->validated());

        return back()->with('success', 'Status permohonan diperbarui.');
    }

    public function destroy(InformationRequest $informationRequest)
    {
        $informationRequest->delete();

        return redirect()->route('admin.information-requests.index')->with('success', 'Permohonan dihapus.');
    }
}
