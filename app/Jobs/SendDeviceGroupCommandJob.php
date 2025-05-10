<?php

namespace App\Jobs;

use App\Models\DeviceGroupCommand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Enums\CommandStatus;
use Laravel\Telescope\Watchable;


class SendDeviceGroupCommandJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public DeviceGroupCommand $command) {}

    public function handle(): void
    {
        $this->command->deliverToDevices();
        $this->command->update(['status' => CommandStatus::Sent]);

        \Log::info('[SendDeviceGroupCommandJob] Comando enviado com sucesso.', [
            'command_uuid' => $this->command->uuid,
            'group' => $this->command->device_group_id,
        ]);
    }
}
