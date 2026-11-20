<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ComplaintChannelRequest;
use App\Models\ComplaintChannel;
use Illuminate\Http\Request;

class ComplaintChannelController extends Controller
{
    public function index(Request $request)
    {
        $channels = ComplaintChannel::query()
            ->when($request->filled('q'), fn ($q) => $q->where('nama', 'like', '%'.$request->q.'%'))
            ->ordered()
            ->paginate(20)->withQueryString();

        return view('admin.complaint-channels.index', compact('channels'));
    }

    public function create()
    {
        return view('admin.complaint-channels.form', ['channel' => new ComplaintChannel(['ikon' => 'bi-globe'])]);
    }

    public function store(ComplaintChannelRequest $request)
    {
        ComplaintChannel::create($this->payload($request));

        return redirect()->route('admin.complaint-channels.index')->with('success', 'Kanal pengaduan berhasil ditambahkan.');
    }

    public function edit(ComplaintChannel $complaintChannel)
    {
        return view('admin.complaint-channels.form', ['channel' => $complaintChannel]);
    }

    public function update(ComplaintChannelRequest $request, ComplaintChannel $complaintChannel)
    {
        $complaintChannel->update($this->payload($request));

        return redirect()->route('admin.complaint-channels.index')->with('success', 'Kanal pengaduan berhasil diperbarui.');
    }

    public function destroy(ComplaintChannel $complaintChannel)
    {
        $complaintChannel->delete();

        return back()->with('success', 'Kanal pengaduan berhasil dihapus.');
    }

    private function payload(ComplaintChannelRequest $request): array
    {
        $data = $request->validated();
        $data['urutan'] = (int) ($data['urutan'] ?? 0);

        return $data;
    }
}
