<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\School;

class FixLastSchoolUuid extends Command
{
    protected $signature = 'fix:last-school';

    protected $description = 'Corrige last_school_uuid para client_admins que têm escola mas não têm last_school_uuid';

    public function handle()
    {
        $users = User::role('client_admin')
            ->whereNull('last_school_uuid')
            ->get();

        if ($users->isEmpty()) {
            $this->info('Todos os client_admin já estão com last_school_uuid definido.');
            return;
        }

        $headers = ['Usuário', 'Email', 'School UUID', 'School Name', 'Status'];
        $rows = [];

        foreach ($users as $user) {
            $school = School::where('client_id', $user->client_id)->first();

            if ($school) {
                // Vincular se ainda não estiver na pivot
                if (! $user->schools()->where('uuid', $school->uuid)->exists()) {
                    $user->schools()->attach((string) $school->uuid);
                }

                $user->update([
                    'last_school_uuid' => (string) $school->uuid
                ]);

                $rows[] = [
                    $user->name,
                    $user->email,
                    $school->uuid,
                    $school->name,
                    '✅ Corrigido'
                ];
            } else {
                $rows[] = [
                    $user->name,
                    $user->email,
                    '—',
                    '—',
                    '❌ Sem escola vinculada'
                ];
            }
        }

        $this->table($headers, $rows);
    }
}
