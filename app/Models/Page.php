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

    public static function sambutanKapolresForDisplay(): self
    {
        return (static::byKey('sambutan_kapolres')
            ?? new static(['key' => 'sambutan_kapolres']))->forDisplay();
    }

    public function forDisplay(): self
    {
        $page = clone $this;

        if ($page->key === 'sambutan_kapolres' && (
            ! trim((string) $page->isi)
            || str_contains($page->isi, '(diisi admin)')
            || str_contains($page->isi, 'teks ini adalah contoh untuk demo')
        )) {
            $page->fill(['judul' => 'Sambutan Kapolres', 'isi' => config('kapolres.sambutan')]);
        }

        return $page;
    }
}
