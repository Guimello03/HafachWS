<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\DeviceGroupCommand;
use App\Models\DeviceCommandLog;
use Illuminate\Support\Facades\Log;

class DeviceCommandController extends Controller
{
    public function getPendingCommand(Request $request)
    {
        $serial = $request->get('deviceId');
        $device = Device::where('serial_number', $serial)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found.'], 404);
        }

        $command = DeviceGroupCommand::where('device_group_id', $device->device_group_id)
            ->where('status', 'pending')
            ->whereDoesntHave('logs', fn ($q) => $q->where('device_id', $device->id))
            ->orderBy('created_at')
            ->first();

        if (!$command) {
            return response()->noContent(); // 204 No Content
        }

        Log::info('COMMAND PAYLOAD ENVIADO PARA O DEVICE:', [
            'comando' => $command->id,
            'payload' => $command->payload,
        ]);

        DeviceCommandLog::create([
            'device_id' => $device->id,
            'device_group_command_id' => $command->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'command_id' => $command->id,
            'verb' => $command->payload['verb'],
            'endpoint' => $command->payload['endpoint'],
            'body' => $command->payload['body'],
            'contentType' => $command->payload['contentType'],
        ]);
    }

    public function storeCommandResult(Request $request)
    {
        Log::info('DEVICE POST RESULT:', $request->all());

        $request->validate([
            'deviceId' => 'required|string',
            'commandId' => 'required|integer',
            'status' => 'required|in:success,error',
        ]);

        $serial = $request->get('deviceId');
        $commandId = $request->get('commandId');
        $status = $request->get('status');

        $device = Device::where('serial_number', $serial)->first();
        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $log = DeviceCommandLog::where('device_id', $device->id)
            ->where('device_group_command_id', $commandId)
            ->first();

        if (!$log) {
            return response()->json(['error' => 'Command log not found'], 404);
        }

        $log->update([
            'status' => $status,
            'executed_at' => now(),
        ]);

        return response()->json(['message' => 'Result logged successfully'], 200);
    }
}
