<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\ComplaintChannel;
use App\Models\Faq;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return view('site.services.index', ['services' => Service::with('unit')->ordered()->get()]);
    }

    public function show(Service $service)
    {
        $service->load(['files', 'unit']);

        return view('site.services.show', [
            'service' => $service,
            'others' => Service::whereKeyNot($service->id)->ordered()->get(),
        ]);
    }

    public function complaints()
    {
        return view('site.services.complaints', ['channels' => ComplaintChannel::ordered()->get()]);
    }

    public function faq()
    {
        return view('site.services.faq', ['faqs' => Faq::ordered()->get()]);
    }
}
