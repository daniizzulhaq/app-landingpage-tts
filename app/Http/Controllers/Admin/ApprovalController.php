<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectRequest;
use App\Models\Agenda;
use App\Models\Album;
use App\Models\Post;
use App\Services\ContentWorkflow;
use Illuminate\Http\Request;

/** Menu "Menunggu Approval" — khusus Super Admin. */
class ApprovalController extends Controller
{
    public function __construct(private ContentWorkflow $workflow)
    {
    }

    public function index()
    {
        return view('admin.approval.index', [
            'posts' => Post::with(['unit', 'author'])->where('status', 'pending')->orderBy('updated_at')->get(),
            'albums' => Album::with(['unit', 'author'])->withCount('photos')->where('status', 'pending')->orderBy('updated_at')->get(),
            'agendas' => Agenda::with(['unit', 'author'])->where('status', 'pending')->orderBy('updated_at')->get(),
        ]);
    }

    public function approvePost(Request $request, Post $post)
    {
        $this->authorize('approve', $post);
        $this->workflow->approve($post, $request->user());

        return back()->with('success', '"'.$post->judul.'" disetujui dan sudah tayang.');
    }

    public function rejectPost(RejectRequest $request, Post $post)
    {
        $this->workflow->reject($post, $request->user(), $request->validated('catatan_revisi'));

        return back()->with('success', '"'.$post->judul.'" dikembalikan ke penulis dengan catatan revisi.');
    }

    public function approveAlbum(Request $request, Album $album)
    {
        $this->authorize('approve', $album);
        $this->workflow->approve($album, $request->user());

        return back()->with('success', 'Album "'.$album->judul.'" disetujui dan sudah tayang.');
    }

    public function rejectAlbum(RejectRequest $request, Album $album)
    {
        $this->workflow->reject($album, $request->user(), $request->validated('catatan_revisi'));

        return back()->with('success', 'Album "'.$album->judul.'" dikembalikan dengan catatan revisi.');
    }

    public function approveAgenda(Request $request, Agenda $agenda)
    {
        $this->authorize('approve', $agenda);
        $this->workflow->approve($agenda, $request->user());

        return back()->with('success', 'Agenda "'.$agenda->judul.'" disetujui dan sudah tayang.');
    }

    public function rejectAgenda(RejectRequest $request, Agenda $agenda)
    {
        $this->workflow->reject($agenda, $request->user(), $request->validated('catatan_revisi'));

        return back()->with('success', 'Agenda "'.$agenda->judul.'" dikembalikan dengan catatan revisi.');
    }
}
