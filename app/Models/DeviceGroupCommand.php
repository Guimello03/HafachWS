<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Jobs\SendDeviceGroupCommandJob;



class DeviceGroupCommand extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';           // <- chave primária é 'id'
    public $incrementing = false;           // <- desativa auto-incremento
    protected $keyType = 'string';       

    protected $fillable = ['uuid', 'device_group_id', 'payload', 'status'];
    protected static function booted()
{
    static::creating(function ($model) {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        }
    });
}

protected $casts = [
    'payload' => 'array',
    'status' => \App\Enums\CommandStatus::class,
];

    public function deviceGroup(): BelongsTo
{
    return $this->belongsTo(DeviceGroup::class, 'device_group_id', 'uuid');
}

public function logs(): HasMany
{
    return $this->hasMany(DeviceCommandLog::class, 'device_group_command_id', 'uuid');
}
    public function deliverToDevices(): void
{
    if (!$this->deviceGroup) {
        throw new \Exception("Grupo de dispositivos não encontrado.");
    }

    $loggedDeviceIds = $this->logs()->pluck('device_id')->toArray();

    foreach ($this->deviceGroup->devices as $device) {
        if (!in_array($device->id, $loggedDeviceIds)) {
            DeviceCommandLog::create([
                'device_id' => $device->uuid,
                'device_group_command_id' => $this->uuid,
                'status' => 'pending',
            ]);
        }
    }
}
public static function createAndDispatch(array $data, string $schoolId): self
{
    logger()->info('[CREATE_AND_DISPATCH] Chamado', [
        'payload' => $data['payload']['endpoint'] ?? 'sem endpoint'
    ]);
    $command = self::create($data);

    SendDeviceGroupCommandJob::dispatch($command)
        ->onQueue("school_commands")
        ->delay(now()->addSeconds(1)); 

    return $command;
}
public function tryMarkAsCompleted(): void
{
    if ($this->status === \App\Enums\CommandStatus::Completed) {
        return;
    }

    $hasPending = $this->logs()
        ->where('status', \App\Enums\DeviceCommandLogs::Pending->value)
        ->exists();

    if (!$hasPending) {
        $this->update(['status' => \App\Enums\CommandStatus::Completed]);
        logger()->info("Comando {$this->uuid} marcado como COMPLETED (todos os dispositivos responderam).");
    }
}
}