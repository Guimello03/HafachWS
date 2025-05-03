<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Device extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'device_group_id', 'serial_number', 'model'];
    protected static function booted()
{
    static::creating(function ($model) {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        }
    });
}

    
    public function groups(): BelongsToMany
{
    return $this->belongsToMany(DeviceGroup::class, 'device_device_group');
}
public function externalDeviceIds()
{
    return $this->hasMany(\App\Models\ExternalDeviceId::class);
}
}