<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUnit;
use App\Models\Concerns\HasApproval;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use BelongsToUnit, HasApproval;

    protected $fillable = ['unit_id', 'user_id', 'judul', 'deskripsi', 'lokasi', 'mulai', 'selesai', 'status', 'catatan_revisi', 'approved_by', 'published_at'];

    protected function casts(): array
    {
        return ['mulai' => 'datetime', 'selesai' => 'datetime', 'published_at' => 'datetime'];
    }

    public function scopeUpcoming($query)
    {
        return $query->where(fn ($q) => $q->where('mulai', '>=', now()->startOfDay())->orWhere('selesai', '>=', now()))->orderBy('mulai');
    }

    public function scopePast($query)
    {
        return $query->where('mulai', '<', now()->startOfDay())
            ->where(fn ($q) => $q->whereNull('selesai')->orWhere('selesai', '<', now()))
            ->orderByDesc('mulai');
    }

    public function waktu(): string
    {
        $mulai = $this->mulai;
        if (! $this->selesai) {
            return tgl($mulai, 'dddd, D MMMM YYYY · HH:mm').' WITA';
        }
        if ($mulai->isSameDay($this->selesai)) {
            return tgl($mulai, 'dddd, D MMMM YYYY · HH:mm').'–'.$this->selesai->format('H:i').' WITA';
        }

        return tgl($mulai, 'D MMM YYYY HH:mm').' – '.tgl($this->selesai, 'D MMM YYYY HH:mm').' WITA';
    }
}
