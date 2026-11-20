<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUnit;
use App\Models\Concerns\HasApproval;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use BelongsToUnit, HasApproval, HasSlug;

    public const TYPE_BERITA = 'berita';

    public const TYPE_KEGIATAN = 'kegiatan';

    public const TYPE_PENGUMUMAN = 'pengumuman';

    public const TYPE_EDUKASI = 'edukasi';

    /** type => [label tunggal, label menu publik, ikon] */
    public const TYPES = [
        self::TYPE_BERITA => ['Berita', 'Berita dan Siaran Pers', 'bi-newspaper'],
        self::TYPE_KEGIATAN => ['Kegiatan', 'Kegiatan', 'bi-camera-reels'],
        self::TYPE_PENGUMUMAN => ['Pengumuman', 'Pengumuman', 'bi-megaphone'],
        self::TYPE_EDUKASI => ['Edukasi', 'Edukasi Kamtibmas', 'bi-lightbulb'],
    ];

    protected $fillable = [
        'type', 'unit_id', 'user_id', 'category_id', 'judul', 'slug', 'ringkasan', 'isi', 'thumbnail', 'lokasi',
        'tanggal_kegiatan', 'lampiran', 'is_penting', 'status', 'catatan_revisi', 'approved_by', 'published_at', 'views',
    ];

    protected function casts(): array
    {
        return [
            'is_penting' => 'boolean',
            'tanggal_kegiatan' => 'date',
            'published_at' => 'datetime',
        ];
    }

    public static function typeLabel(string $type, bool $long = false): string
    {
        return self::TYPES[$type][$long ? 1 : 0] ?? Str::headline($type);
    }

    public function label(): string
    {
        return self::typeLabel($this->type);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class)->orderBy('id');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    /** URL halaman publik, contoh: /berita/judul-berita */
    public function url(): string
    {
        return route('site.posts.show', ['type' => $this->type, 'post' => $this->slug]);
    }

    public function excerpt(int $limit = 160): string
    {
        return Str::limit(trim($this->ringkasan ?: html_entity_decode(strip_tags((string) $this->isi))), $limit);
    }

    public function lampiranIsPdf(): bool
    {
        return $this->lampiran && Str::endsWith(strtolower($this->lampiran), '.pdf');
    }

    public function displayDate()
    {
        return $this->published_at ?? $this->created_at;
    }
}
