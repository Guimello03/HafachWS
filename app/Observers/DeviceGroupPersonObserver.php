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
use App\Jobs\SendPhotoToDevicesJob;


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
