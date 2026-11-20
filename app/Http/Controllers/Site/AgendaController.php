<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function __invoke(Request $request)
    {
        $archive = $request->boolean('arsip');
        $query = Agenda::published()->with('unit');

        return view('site.agenda', [
            'archive' => $archive,
            'agendas' => ($archive ? $query->past() : $query->upcoming())->paginate(10)->withQueryString(),
        ]);
    }
}
