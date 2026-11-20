<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UnitSeeder::class,
            RoleUserSeeder::class,
            SiteContentSeeder::class,
            ContentSeeder::class,
            ServiceAgendaSeeder::class,
        ]);
    }
}
