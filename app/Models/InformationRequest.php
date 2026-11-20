<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformationRequest extends Model
{
    public const STATUSES = [
        'baru' => 'Baru',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak',
    ];

    protected $fillable = ['nama', 'kontak', 'informasi_diminta', 'tujuan', 'status', 'catatan', 'ip_address'];

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
