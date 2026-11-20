<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgendaRequest;
use App\Models\Agenda;
use App\Models\Unit;
use App\Services\ContentWorkflow;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __construct(private ContentWorkflow $workflow)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Agenda::class);
        $user = $request->user();

        $agendas = Agenda::ownedBy($user)->with('unit')
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%'.$request->q.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->input('waktu') === 'mendatang', fn ($q) => $q->where('mulai', '>=', now()->startOfDay()))
            ->when($request->input('waktu') === 'lewat', fn ($q) => $q->where('mulai', '<', now()->startOfDay()))
            ->when($request->filled('unit_id') && $user->isSuperAdmin(), fn ($q) => $q->where('unit_id', $request->unit_id))
            ->orderByDesc('mulai')
            ->paginate(20)->withQueryString();

        return view('admin.agendas.index', ['agendas' => $agendas, 'units' => $user->isSuperAdmin() ? Unit::ordered()->get() : collect()]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Agenda::class);

        return view('admin.agendas.form', $this->formData(new Agenda(['unit_id' => $request->user()->unit_id])));
    }

    public function store(AgendaRequest $request)
    {
        $user = $request->user();
        $agenda = new Agenda($request->safe()->only(['judul', 'deskripsi', 'lokasi', 'mulai', 'selesai']));
        $agenda->user_id = $user->id;
        $agenda->unit_id = $user->isSuperAdmin() ? $request->validated('unit_id') : $user->unit_id;
        $agenda->forceFill($this->workflow->statusFor($user, $request->validated('action')));
        $agenda->save();

        return redirect()->route('admin.agendas.index')->with('success', $this->message($agenda));
    }

    public function edit(Agenda $agenda)
    {
        $this->authorize('update', $agenda);

        return view('admin.agendas.form', $this->formData($agenda));
    }

    public function update(AgendaRequest $request, Agenda $agenda)
    {
        $user = $request->user();
        $agenda->fill($request->safe()->only(['judul', 'deskripsi', 'lokasi', 'mulai', 'selesai']));
        if ($user->isSuperAdmin()) {
            $agenda->unit_id = $request->validated('unit_id');
        }
        $agenda->forceFill($this->workflow->statusFor($user, $request->validated('action'), $agenda));
        $agenda->save();

        return redirect()->route('admin.agendas.index')->with('success', $this->message($agenda));
    }

    public function destroy(Agenda $agenda)
    {
        $this->authorize('delete', $agenda);
        $agenda->delete();

        return back()->with('success', 'Agenda berhasil dihapus.');
    }

    private function formData(Agenda $agenda): array
    {
        return [
            'agenda' => $agenda,
            'units' => Unit::ordered()->get()->mapWithKeys(fn ($u) => [$u->id => $u->name])->all(),
            'canPublish' => ContentWorkflow::canPublishDirectly(auth()->user()),
        ];
    }

    private function message(Agenda $agenda): string
    {
        return match ($agenda->status) {
            'published' => 'Agenda berhasil disimpan dan sudah tayang.',
            'pending' => 'Agenda disimpan dan dikirim untuk persetujuan Super Admin.',
            default => 'Agenda disimpan sebagai draft.',
        };
    }
}
