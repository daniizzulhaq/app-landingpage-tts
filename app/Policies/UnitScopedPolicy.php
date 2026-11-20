<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Policy dasar untuk konten milik unit.
 * Super Admin selalu diizinkan lewat Gate::before (AppServiceProvider).
 * Admin Unit hanya boleh mengelola data dengan unit_id yang sama.
 */
abstract class UnitScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->unit_id !== null;
    }

    public function view(User $user, Model $model): bool
    {
        return $this->owns($user, $model);
    }

    public function create(User $user): bool
    {
        return $user->unit_id !== null;
    }

    public function update(User $user, Model $model): bool
    {
        return $this->owns($user, $model);
    }

    public function delete(User $user, Model $model): bool
    {
        return $this->owns($user, $model);
    }

    /** Persetujuan konten hanya untuk Super Admin. */
    public function approve(User $user, Model $model): bool
    {
        return false;
    }

    protected function owns(User $user, Model $model): bool
    {
        return $user->unit_id !== null && (int) $model->unit_id === (int) $user->unit_id;
    }
}
