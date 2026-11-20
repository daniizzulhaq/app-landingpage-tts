<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    public const SUPER_ADMIN = 'super-admin';

    public const ADMIN_UNIT = 'admin-unit';

    public const ROLES = [
        self::SUPER_ADMIN => 'Super Admin',
        self::ADMIN_UNIT => 'Admin Unit',
    ];

    protected $fillable = ['name', 'email', 'password', 'unit_id', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::SUPER_ADMIN);
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->getRoleNames()->first()] ?? '-';
    }
}
