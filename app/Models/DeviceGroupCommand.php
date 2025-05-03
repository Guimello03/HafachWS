<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;


class DeviceGroupCommand extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'device_group_id', 'payload', 'status'];
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
    ];

    public function deviceGroup(): BelongsTo
    {
        return $this->belongsTo(DeviceGroup::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(DeviceCommandLog::class);
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
                'device_id' => $device->id,
                'device_group_command_id' => $this->id,
                'status' => 'pending',
            ]);
        }
    }
}
}