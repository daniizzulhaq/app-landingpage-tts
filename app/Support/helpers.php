<?php

use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    /** Ambil nilai pengaturan situs (di-cache). */
    function setting(string $key, mixed $default = null): mixed
    {
        $value = Setting::allCached()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }
}

if (! function_exists('media_url')) {
    /** URL file di disk public, atau placeholder jika kosong. */
    function media_url(?string $path, string $placeholder = 'img/placeholder.svg'): string
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/'.ltrim($path, '/'));
        }

        return asset($placeholder);
    }
}

if (! function_exists('thumb_url')) {
    /** URL thumbnail 600px (dibuat ImageService), fallback ke gambar utama. */
    function thumb_url(?string $path, string $placeholder = 'img/placeholder.svg'): string
    {
        if ($path) {
            $thumb = \App\Services\ImageService::thumbPath($path);
            if (Storage::disk('public')->exists($thumb)) {
                return asset('storage/'.ltrim($thumb, '/'));
            }
        }

        return media_url($path, $placeholder);
    }
}

if (! function_exists('tgl')) {
    /** Format tanggal Indonesia. */
    function tgl(mixed $date, string $format = 'D MMMM YYYY'): string
    {
        if (! $date) {
            return '-';
        }

        return Carbon::parse($date)->locale('id')->isoFormat($format);
    }
}

if (! function_exists('youtube_id')) {
    function youtube_id(?string $url): ?string
    {
        if (! $url) {
            return null;
        }
        preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})~', $url, $m);

        return $m[1] ?? null;
    }
}

if (! function_exists('tel_link')) {
    function tel_link(?string $phone): string
    {
        return 'tel:'.preg_replace('/[^0-9+]/', '', (string) $phone);
    }
}
