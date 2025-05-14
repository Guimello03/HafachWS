<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['uuid', 'school_id', 'serial_number', 'model'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('uuid')) {
                $model->uuid = $model->getOriginal('uuid');
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(DeviceGroup::class, 'device_device_group', 'device_id', 'device_group_id');
    }

    public function externalDeviceIds()
    {
        return $this->hasMany(\App\Models\ExternalDeviceId::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'uuid');
    }
    public function getNextPendingCommand(): ?\App\Models\DeviceGroupCommand
{
    return \App\Models\DeviceGroupCommand::where('status', \App\Enums\CommandStatus::Pending)
        ->whereIn('device_group_id', $this->groups()->pluck('device_groups.uuid'))
        ->orderBy('created_at')
        ->first();
}
public function status()
{
    return $this->hasOne(\App\Models\DeviceStatus::class, 'device_id', 'uuid');
}

}
