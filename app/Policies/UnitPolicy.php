<?php

namespace App\Policies;

use App\Models\Unit;
use App\Models\User;

/** Admin Unit hanya boleh melihat & mengubah profil unitnya sendiri. */
class UnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->unit_id !== null;
    }

    public function update(User $user, Unit $unit): bool
    {
        return (int) $user->unit_id === (int) $unit->id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function delete(User $user, Unit $unit): bool
    {
        return false;
    }
}
