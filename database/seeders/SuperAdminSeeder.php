<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['super_admin', 'client_admin', 'school_director'] as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
        // Cria o usuário Super Admin
        $user = User::create([
            'name' => 'Guilherme Mello',
            'email' => 'admin@hafachws.com.br',
            'password' => Hash::make('password'), // Senha inicial segura
        ]);

        // Atribui a Role super_admin
        $user->assignRole('super_admin');
    }
}
