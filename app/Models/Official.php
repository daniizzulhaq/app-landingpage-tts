<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Official extends Model
{
    public const LEVELS = [
        'kapolres' => 'Kapolres',
        'wakapolres' => 'Wakapolres',
        'pejabat' => 'Pejabat Utama / Kepala Unit',
    ];

    protected $fillable = ['unit_id', 'nama', 'pangkat', 'jabatan', 'level', 'foto', 'tugas', 'urutan'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function namaLengkap(): string
    {
        return trim(($this->pangkat ? $this->pangkat.' ' : '').$this->nama);
    }

    public static function kapolresForDisplay(): self
    {
        return (static::where('level', 'kapolres')->orderBy('urutan')->first()
            ?? new static(['level' => 'kapolres']))->forDisplay();
    }

    public function forDisplay(): self
    {
        $official = clone $this;

        if ($official->level === 'kapolres' && (
            ! trim((string) $official->nama)
            || str_contains($official->nama, '(diisi admin)')
            || str_starts_with($official->nama, 'Contoh Nama Kapolres')
        )) {
            $official->fill([
                'nama' => config('kapolres.nama'),
                'pangkat' => config('kapolres.pangkat'),
                'jabatan' => config('kapolres.jabatan'),
                'tugas' => null,
            ]);
        }

        return $official;
    }

    public function fotoUrl(): string
    {
        return media_url($this->foto, $this->level === 'kapolres' ? config('kapolres.foto') : 'img/avatar.svg');
    }
}
