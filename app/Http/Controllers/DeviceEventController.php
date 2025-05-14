<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceEvent;
use App\Models\ExternalDeviceId;
use App\Helpers\GetSchoolSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeviceEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    public function receiveDao(Request $request)
    {
        Log::info('RECEBIDO RAW:', ['raw' => $request->getContent()]);
        Log::info('RECEBIDO PARSED:', ['parsed' => $request->all()]);

        $body = $request->all();
        $values = $body['object_changes'][0]['values'] ?? null;

        if (!$values || !isset($values['user_id'], $values['device_id'], $values['time'])) {
            return response()->json(['error' => 'Campos obrigatórios não encontrados no JSON recebido.'], 422);
        }

        $userId = (int) $values['user_id'];
        $deviceSerial = (string) $values['device_id'];
        $eventTimeStamp = (int) $values['time'];

        Log::info('Evento DAO tratado corretamente', [
            'user_id' => $userId,
            'device_id' => $deviceSerial,
            'time' => $eventTimeStamp
        ]);

        $device = Device::with('school')->where('serial_number', $deviceSerial)->first();
        if (!$device) {
            Log::warning('Dispositivo não localizado', ['serial_number' => $deviceSerial]);
            return response()->json(['error' => 'Dispositivo não localizado.'], 404);
        }

        $external = ExternalDeviceId::with('device.school')
    ->where('external_id', $userId)
    ->where('device_id', $device->uuid)
    ->first();

if (!$external || !$external->device) {
    Log::warning('Pessoa ou dispositivo não encontrada corretamente no evento DAO', [
        'user_id' => $userId,
        'device_uuid' => $device->uuid
    ]);
    return response()->json(['error' => 'Pessoa ou dispositivo não localizada no sistema.'], 404);
}

$eventTime = Carbon::createFromTimestamp($eventTimeStamp);

$deviceLoaded = $external->device;
$schoolIdCampo = $deviceLoaded->school_id ?? null;
$schoolIdRelation = $deviceLoaded->school->uuid ?? null;

Log::info('DEBUG Device carregado corretamente', [
    'device_uuid' => $deviceLoaded->uuid,
    'school_id_campo' => $schoolIdCampo,
    'school_id_relation' => $schoolIdRelation,
]);

$toleranceRaw = GetSchoolSetting::get($schoolIdCampo ?? $schoolIdRelation, 'event_tolerance_minutes', 0);
$toleranceMinutes = max(0, (int) $toleranceRaw);

Log::info('Tolerância configurada corretamente', [
    'tolerance_raw' => $toleranceRaw,
    'tolerance_cast' => (int) $toleranceRaw,
    'tolerance_final' => $toleranceMinutes
]);

$startOfDay = $eventTime->copy()->startOfDay();

$lastEventToday = DeviceEvent::where('person_id', $external->person_id)
    ->where('person_type', $external->person_type)
    ->whereBetween('date', [$startOfDay, $eventTime])
    ->orderByDesc('date')
    ->first();

if (!$lastEventToday) {
    $newDirection = 'in';
    Log::info('Primeira marcação do dia, obrigatoriamente IN');
} else {
    $diffMinutes = $lastEventToday->date->diffInMinutes($eventTime);

    Log::info('Último evento hoje', [
        'data_ultimo_evento' => $lastEventToday->date->toDateTimeString(),
        'direction_ultimo' => $lastEventToday->direction,
        'diff_minutos' => $diffMinutes,
        'tolerancia' => $toleranceMinutes
    ]);

    if ($diffMinutes <= $toleranceMinutes) {
        $newDirection = $lastEventToday->direction;
        Log::info('Dentro da tolerância, mantendo mesma direção: ' . $newDirection);
    } else {
        $newDirection = $lastEventToday->direction === 'in' ? 'out' : 'in';
        Log::info('Fora da tolerância, invertendo direção para: ' . $newDirection);
    }
}

DeviceEvent::create([
    'person_id' => $external->person_id,
    'person_type' => $external->person_type,
    'device_id' => $deviceLoaded->uuid,
    'date' => $eventTime,
    'direction' => $newDirection,
]);

Log::info('Evento DAO registrado com sucesso', [
    'person_id' => $external->person_id,
    'direction' => $newDirection,
    'hora' => $eventTime->format('H:i:s'),
    'tolerance' => $toleranceMinutes
]);

return response()->json([
    'status' => 'ok',
    'direction' => $newDirection,
]);
    }}