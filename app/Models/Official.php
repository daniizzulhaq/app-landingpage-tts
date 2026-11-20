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
}
