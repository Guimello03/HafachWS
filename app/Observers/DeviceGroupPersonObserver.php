<?php

namespace App\Observers;

use App\Models\DeviceGroupAutoTarget;
use Illuminate\Database\Eloquent\Model;
use App\Services\DeviceGroupSyncService;
use App\Models\{
    DeviceGroup,
    ExternalDeviceId,
    DeviceGroupCommand
};

class DeviceGroupPersonObserver
{
    public function created(Model $person)
    {
        logger()->info('[Observer] Entrou no método created para:', [
            'model' => get_class($person),
            'uuid' => $person->uuid,
            'school_id' => $person->school_id,
        ]);
        $targetType = get_class($person);

        $groups = DeviceGroupAutoTarget::where('target_type', $targetType)
            ->whereHas('deviceGroup', fn ($q) => $q->where('school_id', $person->school_id))
            ->with('deviceGroup')
            ->get();
            logger()->info('[Observer] Grupos encontrados para auto-target:', [
                'count' => $groups->count(),
                'grupos' => $groups->pluck('deviceGroup.name')
            ]);

        foreach ($groups as $target) {
            DeviceGroupSyncService::addPersonsToGroup($target->deviceGroup, collect([[
                'uuid' => $person->uuid,
                'type' => $targetType,
                'relation' => DeviceGroupSyncService::relationMethodFromType($targetType),
            ]]));
        }
    }

    public function updated(Model $person)
{
    if ($person->wasChanged('photo_path')) {
        logger()->info('[Observer] Foto alterada, enviando para dispositivos...', [
            'uuid' => $person->uuid,
            'model' => get_class($person),
        ]);

        // Pegamos todos os grupos aos quais essa pessoa está vinculada
        $deviceGroups = $person->deviceGroups()->get(); // ✅ Correto


        foreach ($deviceGroups as $group) {
            self::sendPhotoToDevices($group, $person);
        }
    }
}
    public static function sendPhotoToDevices($deviceGroup, Model $person)
{
    $external = ExternalDeviceId::where('person_id', $person->uuid)
        ->where('person_type', get_class($person))
        ->whereIn('device_id', $deviceGroup->devices->pluck('uuid'))
        ->get();

    if ($external->isEmpty()) {
        logger()->warning('[DeviceSync] Pessoa sem ID externo no grupo', [
            'uuid' => $person->uuid,
            'group' => $deviceGroup->name,
        ]);
        return;
    }

    $base64 = \App\Helpers\MediaHelper::getBase64UserPhoto($person->uuid);
    if (!$base64) return;

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
        'device_group_id' => $deviceGroup->uuid,
        'payload' => $payload,
        'status' => \App\Enums\CommandStatus::Pending,
    ], $deviceGroup->school_id);
}

public function deleting(Model $person)
{
    $relation = DeviceGroupSyncService::relationMethodFromType(get_class($person));
    $groups = $person->deviceGroups()->get();

    foreach ($groups as $group) {
        DeviceGroupSyncService::removePersonsFromGroup(
            $group,
            collect([[
                'uuid' => $person->uuid,
                'type' => get_class($person),
                'relation' => $relation,
            ]])
        );
    }
}
}
