<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\DeviceCommandLog;
use App\Models\DeviceGroupCommand;

class ClearPendingCommands extends Command
{
    protected $signature = 'commands:clear';
    protected $description = 'Deleta todos os comandos pendentes e seus logs associados';

    public function handle(): int
    {
        DB::transaction(function () {
            $pendingCommands = DeviceGroupCommand::where('status', 'pending')->pluck('uuid');

            DeviceCommandLog::whereIn('device_group_command_id', $pendingCommands)->delete();
            DeviceGroupCommand::whereIn('uuid', $pendingCommands)->delete();
        });

        $this->info('Comandos pendentes e logs relacionados foram deletados com sucesso.');

        return Command::SUCCESS;
    }
}
