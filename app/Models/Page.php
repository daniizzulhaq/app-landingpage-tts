<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public const KEYS = [
        'sejarah' => 'Sejarah',
        'visi_misi' => 'Visi dan Misi',
        'tugas_fungsi' => 'Tugas dan Fungsi',
        'wilayah_hukum' => 'Wilayah Hukum',
        'sambutan_kapolres' => 'Sambutan Kapolres',
    ];

    protected $fillable = ['key', 'judul', 'isi', 'gambar'];

    public static function byKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }
}
