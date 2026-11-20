<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (array_keys(User::ROLES) as $role) {
            Role::findOrCreate($role, 'web');
        }

        $super = User::updateOrCreate(
            ['email' => 'admin@polrestts.test'],
            ['name' => 'Super Admin (Sie Humas/TIK)', 'password' => 'password', 'is_active' => true, 'unit_id' => null]
        );
        $super->syncRoles([User::SUPER_ADMIN]);

        $unit = Unit::where('slug', 'sat-lantas')->first();
        $operator = User::updateOrCreate(
            ['email' => 'operator.lantas@polrestts.test'],
            ['name' => 'Operator Sat Lantas', 'password' => 'password', 'is_active' => true, 'unit_id' => $unit?->id]
        );
        $operator->syncRoles([User::ADMIN_UNIT]);
    }
}
