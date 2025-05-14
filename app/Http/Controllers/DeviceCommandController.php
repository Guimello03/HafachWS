<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\DeviceCommandLog;
use App\Models\DeviceStatus;

use App\Http\Requests\StoreCommandResultRequest;
use App\Http\Resources\DeviceCommandResource;
use App\Enums\CommandStatus;
use App\Enums\DeviceCommandLogs;
use App\Services\Devices\Handlers\UserCreationResponseHandler;

class DeviceCommandController extends Controller
{
    public function getPendingCommand(Request $request)
{
    $serial = $request->get('deviceId');
    $device = Device::where('serial_number', $serial)->firstOrFail();
      // Atualiza ou cria o status com o timestamp atual
       DeviceStatus::updateOrCreate(
        ['device_id' => $device->uuid],
        ['last_seen' => now()]
    );

    // 1. Buscar o log com status 'pending' e comando 'sent'
    $log = DeviceCommandLog::where('device_id', $device->uuid)
        ->where('status', CommandStatus::Pending)
        ->whereHas('command', function ($q) {
            $q->where('status', CommandStatus::Sent);
        })
        ->first();

    if (!$log) {
        return response()->noContent(); // 204
    }
   

    // 2. Recuperar o comando vinculado
    $command = $log->command;
    logger()->info('[getPendingCommand] Entregando comando ao equipamento', [
        'device_id' => $device->uuid,
        'command' => $command->payload['body']['object'] ?? null,
        'endpoint' => $command->payload['endpoint'] ?? null,
        'command_uuid' => $command->uuid,
    ]);

    // 3. Mapeamento cache → equipamento x comando
    $equipmentUuid = $request->get('uuid');
    if ($equipmentUuid) {
        cache()->put("cmdmap:{$equipmentUuid}:{$device->uuid}", $command->uuid, now()->addMinutes(5));
    }

    // 4. Atualiza log como "em entrega"
    DeviceCommandLog::logDelivery($device, $command);

    // 5. Retorna o comando para o equipamento
    return response()->json([
        'command_id'  => $command->uuid,
        'verb'        => $command->payload['verb'] ?? 'POST',
        'endpoint'    => $command->payload['endpoint'] ?? '',
        'body'        => $command->payload['body'] ?? [],
        'contentType' => $command->payload['contentType'] ?? 'application/json',
    ]);
}

    public function storeCommandResult(\App\Http\Requests\StoreCommandResultRequest $request)

    {
       

        logger()->info('[storeCommandResult] Request recebido', $request->all());

        $device = \App\Models\Device::where('serial_number', $request->input('deviceId'))->firstOrFail();

        $equipmentUuid = $request->input('uuid');
        $deviceId = $device->uuid;

        $commandUuid = cache()->pull("cmdmap:{$equipmentUuid}:{$deviceId}");

        if (!$commandUuid) {
            abort(404, 'Comando não encontrado (expirado ou inexistente).');
        }

       
        $log = \App\Models\DeviceCommandLog::where('device_id', $device->uuid)
            ->where('device_group_command_id', $commandUuid)
            ->firstOrFail();

            $logStatus = $request->getStatusEnum();

            

        $log->markAsExecuted($logStatus);

        logger()->info('[storeCommandResult] Log encontrado', [
            'device_id'  => $log->device_id,
            'command_id' => $log->device_group_command_id,
        ]);

        // 🧠 Aqui estava o erro! Estava usando CommandStatus::Pending indevidamente.
        $log->command->tryMarkAsCompleted();

        $response = $request->input('response');
        $decoded = is_string($response) ? json_decode($response, true) : $response;
        $endpoint = $log->command->payload['endpoint'] ?? null;

        if ($endpoint === 'create_objects' && isset($decoded['ids']) && is_array($decoded['ids'])) {
            \App\Services\Devices\Handlers\UserCreationResponseHandler::handle([
                'ids'      => $decoded['ids'],
                'deviceId' => $device->uuid,
                'uuid'     => $commandUuid,
                'endpoint' => $endpoint,
            ]);
        }

        return response()->json(['message' => 'Result logged successfully']);
    }
}
