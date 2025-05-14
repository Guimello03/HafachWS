<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{DeviceGroup, ExternalDeviceId, DeviceGroupCommand};
use App\Helpers\MediaHelper;
use App\Enums\CommandStatus;

use Illuminate\Database\Eloquent\Model;

class SendPhotoToDevicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deviceGroup;
    public $person;

    public function __construct(DeviceGroup $deviceGroup, Model $person)
    {
        $this->deviceGroup = $deviceGroup;
        $this->person = $person;
    }

    public function handle(): void
    {
        $external = ExternalDeviceId::where('person_id', $this->person->uuid)
            ->where('person_type', get_class($this->person))
            ->whereIn('device_id', $this->deviceGroup->devices->pluck('uuid'))
            ->get();

        if ($external->isEmpty()) {
            logger()->warning('[DeviceSync][Job] Pessoa sem ID externo no grupo', [
                'uuid' => $this->person->uuid,
                'group' => $this->deviceGroup->name,
            ]);
            return;
        }

        $base64 = MediaHelper::getBase64UserPhoto($this->person->uuid);
        if (!$base64) {
            logger()->error('[DeviceSync][Job] Falha ao obter imagem base64', [
                'uuid' => $this->person->uuid,
            ]);
            return;
        }

        $payload = [
            'verb' => 'POST',
            'endpoint' => 'user_set_image_list',
            'contentType' => 'application/json',
            'body' => [
                'match' => false,
                'user_images' => $external->map(fn ($e) => [
                    'user_id' => $e->external_id,
                    'timestamp' => now()->timestamp,
                    'image' => $base64
                ])->values()->all(),
            ]
        ];

        DeviceGroupCommand::createAndDispatch([
            'device_group_id' => $this->deviceGroup->uuid,
            'payload' => $payload,
            'status' => CommandStatus::Pending,
        ], $this->deviceGroup->school_id);
    }
}