<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cria o cliente
        $client = Client::firstOrCreate(
            ['email' => 'contato@hafach.com.br'],
            [
                'name' => 'Hafach Cliente',
                'cnpj' => '00.000.000/0001-00',
            ]
        );

        // 2. Cria a escola
        $school = School::firstOrCreate(
            ['name' => 'Hafach School'],
            [
                'cnpj' => '00.000.000/0001-99',
                'uuid' => (string) Str::uuid(),
                'client_id' => $client->id,
            ]
        );

        // 3. Cria o super admin sem vínculo, mas com last_school_uuid
        $admin = User::updateOrCreate(
            ['email' => 'admin@hafach.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'last_school_uuid' => $school->uuid,
            ]
        );

        // 4. Define o papel
        $admin->assignRole('super_admin');
    }
}
