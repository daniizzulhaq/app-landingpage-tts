<?php

namespace App\Models\Concerns;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Konten milik unit. Admin Unit hanya boleh melihat data unitnya sendiri.
 */
trait BelongsToUnit
{
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function scopeOwnedBy(Builder $query, User $user): Builder
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->where($this->getTable().'.unit_id', $user->unit_id ?? 0);
    }
}
