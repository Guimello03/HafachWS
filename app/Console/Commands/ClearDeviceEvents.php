<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DeviceEvent;

class ClearDeviceEvents extends Command
{
    protected $signature = 'events:clear';
    protected $description = 'Remove todas as marcações (DeviceEvents) do sistema.';

    public function handle()
    {
        if ($this->confirm('Tem certeza que deseja apagar TODAS as marcações? Isso não pode ser desfeito.')) {
            $deleted = DeviceEvent::truncate();

            $this->info('✅ Todas as marcações foram apagadas com sucesso.');
        } else {
            $this->info('Operação cancelada.');
        }
    }
}
