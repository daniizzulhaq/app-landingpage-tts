<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUnit;
use App\Models\Concerns\HasApproval;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Album extends Model
{
    use BelongsToUnit, HasApproval, HasSlug;

    protected $fillable = [
        'unit_id', 'user_id', 'judul', 'slug', 'deskripsi', 'tanggal', 'status', 'catatan_revisi', 'approved_by', 'published_at',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date', 'published_at' => 'datetime'];
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class)->orderBy('urutan')->orderBy('id');
    }

    public function cover(): HasOne
    {
        return $this->hasOne(Photo::class)->orderBy('urutan')->orderBy('id');
    }
}
