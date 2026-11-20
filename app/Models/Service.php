<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasSlug;

    public const ICONS = [
        'bi-card-checklist' => 'Daftar periksa',
        'bi-person-vcard' => 'Kartu identitas (SIM)',
        'bi-file-earmark-check' => 'Dokumen/surat (SKCK)',
        'bi-people' => 'Keramaian',
        'bi-search' => 'Kehilangan',
        'bi-car-front' => 'Kendaraan',
        'bi-shield-check' => 'Keamanan',
        'bi-telephone' => 'Telepon',
        'bi-clipboard-check' => 'Laporan',
        'bi-building' => 'Gedung',
    ];

    protected string $slugSource = 'nama';

    protected $fillable = ['nama', 'slug', 'ikon', 'unit_id', 'deskripsi', 'persyaratan', 'prosedur', 'biaya', 'jadwal', 'lokasi', 'urutan'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ServiceFile::class)->orderBy('id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }

    /** Teks multi-baris -> array item (untuk daftar persyaratan / prosedur bernomor). */
    public static function lines(?string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $text))
            ->map(fn ($l) => trim(preg_replace('/^\s*(\d+[.)]|[-*•])\s*/u', '', $l)))
            ->filter()->values()->all();
    }
}
