<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Deleta todos os registros antigos
        Role::query()->delete();

        // Cria as roles novamente
        Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        Role::create([
            'name' => 'client_admin',
            'guard_name' => 'web',
        ]);

        Role::create([
            'name' => 'school_director',
            'guard_name' => 'web',
        ]);
    }
}
