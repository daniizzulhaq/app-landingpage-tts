<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    /** Klasifikasi informasi publik (UU No. 14 Tahun 2008 tentang KIP). */
    public const CATEGORIES = [
        'berkala' => 'Informasi Berkala',
        'serta_merta' => 'Informasi Serta Merta',
        'setiap_saat' => 'Informasi Tersedia Setiap Saat',
        'lainnya' => 'Dokumen Lainnya',
    ];

    protected $fillable = ['judul', 'kategori', 'deskripsi', 'path', 'tahun'];

    public function kategoriLabel(): string
    {
        return self::CATEGORIES[$this->kategori] ?? $this->kategori;
    }

    public function extension(): string
    {
        return strtoupper(pathinfo($this->path, PATHINFO_EXTENSION));
    }

    public function sizeLabel(): string
    {
        $disk = Storage::disk('public');
        if (! $disk->exists($this->path)) {
            return '-';
        }
        $kb = $disk->size($this->path) / 1024;

        return $kb > 1024 ? number_format($kb / 1024, 1).' MB' : number_format($kb, 0).' KB';
    }
}
