<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Album;
use App\Models\InformationRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $super = $user->isSuperAdmin();

        // Jumlah konten per jenis (Admin Unit: hanya unitnya)
        $counts = Post::ownedBy($user)->selectRaw("type, COUNT(*) as total, SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as tayang")
            ->groupBy('type')->get()->keyBy('type');

        $stats = [];
        foreach (Post::TYPES as $type => [$label, , $icon]) {
            if ($type === Post::TYPE_PENGUMUMAN && ! $super) {
                continue;
            }
            $stats[] = [
                'label' => $label, 'icon' => $icon,
                'total' => (int) ($counts[$type]->total ?? 0),
                'tayang' => (int) ($counts[$type]->tayang ?? 0),
                'url' => route('admin.posts.index', $type),
            ];
        }
        $stats[] = ['label' => 'Album Foto', 'icon' => 'bi-images', 'total' => Album::ownedBy($user)->count(),
            'tayang' => Album::ownedBy($user)->where('status', 'published')->count(), 'url' => route('admin.albums.index')];
        $stats[] = ['label' => 'Agenda', 'icon' => 'bi-calendar-event', 'total' => Agenda::ownedBy($user)->count(),
            'tayang' => Agenda::ownedBy($user)->where('status', 'published')->count(), 'url' => route('admin.agendas.index')];

        $pendingCount = Post::ownedBy($user)->where('status', 'pending')->count()
            + Album::ownedBy($user)->where('status', 'pending')->count()
            + Agenda::ownedBy($user)->where('status', 'pending')->count();

        return view('admin.dashboard', [
            'stats' => $stats,
            'pendingCount' => $pendingCount,
            'pendingPosts' => Post::ownedBy($user)->with('unit')->where('status', 'pending')->latest('updated_at')->limit(5)->get(),
            'rejected' => $super ? collect() : Post::ownedBy($user)->where('status', 'rejected')->latest('updated_at')->limit(5)->get(),
            'newRequests' => $super ? InformationRequest::where('status', 'baru')->count() : null,
            'latestRequests' => $super ? InformationRequest::latest()->limit(5)->get() : collect(),
            'popular' => Post::ownedBy($user)->published()->orderByDesc('views')->limit(5)->get(),
        ]);
    }
}
