<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

/** Kosongkan cache sitemap setiap konten publik berubah. */
class ContentCacheObserver
{
    public function saved(): void
    {
        Cache::forget('sitemap.xml');
    }

    public function deleted(): void
    {
        Cache::forget('sitemap.xml');
    }
}
