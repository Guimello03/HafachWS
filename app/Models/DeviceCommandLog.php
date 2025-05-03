<?php

namespace App\Models;

use App\Enums\CommandStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceCommandLog extends Model
{
    protected $table = 'device_command_logs';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'device_id',
        'device_group_command_id',
        'status',
        'executed_at'
    ];

    protected $casts = [
        'executed_at' => 'datetime',
        'status' => CommandStatus::class, // 👈 Cast automático de enum
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function command(): BelongsTo
    {
        return $this->belongsTo(DeviceGroupCommand::class, 'device_group_command_id');
    }

    public function markAsExecuted(CommandStatus $status = CommandStatus::Success): void
    {
        $this->update([
            'status' => $status,
            'executed_at' => now(),
        ]);
    }
}
