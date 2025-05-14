<?php
namespace App\Services;

use App\Models\DeviceEvent;
use Carbon\Carbon;

class UserStatusService
{
    public static function getLastStatus($personId, $personType, Carbon $referenceTime = null)
    {
        $referenceTime = $referenceTime ?? Carbon::now();
        $startOfDay = $referenceTime->copy()->startOfDay();
        
        return DeviceEvent::where('person_id', $personId)
            ->where('person_type', $personType)
            ->whereBetween('date', [$startOfDay, $referenceTime])
            ->orderBy('date', 'desc')
            ->first();
    }

    /**
     * Retorna o último status de todos os usuários no dia atual até agora.
     */
    public static function getAllLastStatusToday()
    {
        $startOfDay = Carbon::now()->startOfDay();
        $now = Carbon::now();

        $events = DeviceEvent::whereBetween('date', [$startOfDay, $now])
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy(function ($event) {
                return $event->person_id . '|' . $event->person_type;
            })
            ->map(function ($events) {
                $last = $events->first();
                return [
                    'person_id' => $last->person_id,
                    'person_type' => $last->person_type,
                    'direction' => $last->direction,
                    'date' => $last->date->toDateTimeString(),
                    'device_id' => $last->device_id
                ];
            });

        return $events->values();
    }
}
