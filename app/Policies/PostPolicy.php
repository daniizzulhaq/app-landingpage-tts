<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy extends UnitScopedPolicy
{
    /** Admin Unit tidak boleh membuat pengumuman. */
    public function createType(User $user, string $type): bool
    {
        return $user->unit_id !== null && $type !== Post::TYPE_PENGUMUMAN;
    }
}
