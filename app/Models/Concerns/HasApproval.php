<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Status konten: draft -> pending -> published | rejected.
 */
trait HasApproval
{
    public static array $statusLabels = [
        'draft' => 'Draft',
        'pending' => 'Menunggu Persetujuan',
        'published' => 'Tayang',
        'rejected' => 'Dikembalikan',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /** Hanya konten yang tayang (dipakai di semua halaman publik). */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where($this->getTable().'.status', 'published')
            ->where(fn ($q) => $q->whereNull($this->getTable().'.published_at')->orWhere($this->getTable().'.published_at', '<=', now()));
    }

    public function statusLabel(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
