<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintChannel extends Model
{
    /** Pilihan ikon (Bootstrap Icons) untuk kanal pengaduan. */
    public const ICONS = [
        'bi-telephone' => 'Telepon',
        'bi-whatsapp' => 'WhatsApp',
        'bi-globe' => 'Situs web',
        'bi-envelope' => 'Email',
        'bi-phone' => 'Aplikasi',
        'bi-chat-dots' => 'Pesan / chat',
        'bi-shield-check' => 'Propam / pengawasan',
        'bi-megaphone' => 'Aspirasi',
        'bi-link-45deg' => 'Tautan',
    ];

    protected $fillable = ['nama', 'deskripsi', 'url', 'ikon', 'urutan'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('id');
    }
}
