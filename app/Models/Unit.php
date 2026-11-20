<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasSlug;

    public const TYPES = [
        'bag' => 'Bagian',
        'sat' => 'Satuan',
        'sie' => 'Seksi',
        'polsek' => 'Polsek',
    ];

    protected string $slugSource = 'name';

    protected $fillable = [
        'type', 'name', 'slug', 'deskripsi', 'tugas_pokok', 'layanan_unit', 'alamat', 'telepon',
        'email', 'wilayah_pelayanan', 'maps_url', 'foto', 'urutan',
    ];

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function officials(): HasMany
    {
        return $this->hasMany(Official::class)->orderBy('urutan');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('name');
    }

    /** Link petunjuk arah Google Maps. */
    public function directionsUrl(): string
    {
        if ($this->maps_url && str_starts_with($this->maps_url, 'http')) {
            return $this->maps_url;
        }

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode(trim($this->name.' '.$this->alamat));
    }
}
