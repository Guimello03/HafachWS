<?php

namespace App\Models;

use App\Enums\CommandStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\DeviceCommandLogs;
use Illuminate\Support\Facades\Log;



class DeviceCommandLog extends Model
{
    protected $table = 'device_command_logs';
    public $incrementing = false;
    public $timestamps = true;

    // ❌ NÃO declare $primaryKey nem $keyType nem use uuid

    protected $fillable = [
        'device_id',
        'device_group_command_id',
        'status',
        'executed_at'
    ];

    protected $casts = [
        'executed_at' => 'datetime',
        'status' => CommandStatus::class,
    ];
    protected $primaryKey = null;

    public function getKeyName()
    {
        return null;
    }
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function command(): BelongsTo
    {
        return $this->belongsTo(DeviceGroupCommand::class, 'device_group_command_id');
    }

    public function markAsExecuted(DeviceCommandLogs $status = DeviceCommandLogs::Success): void
    {
        $updated = static::where('device_id', $this->device_id)
            ->where('device_group_command_id', $this->device_group_command_id)
            ->update([
                'status' => $status->value,
                'executed_at' => now(),
            ]);
    
        if ($updated) {
            Log::info("CommandLog atualizado: {$this->device_id} | {$this->device_group_command_id} → {$status->value}");
        } else {
            Log::warning("Falha ao atualizar CommandLog: {$this->device_id} | {$this->device_group_command_id}");
        }
    }

public static function logDelivery(Device $device, DeviceGroupCommand $command): self
{
    $log = self::firstOrNew([
        'device_id' => $device->uuid,
        'device_group_command_id' => $command->uuid,
    ]);

    $log->status = CommandStatus::Pending;
    $log->save();

    return $log;
}
}
