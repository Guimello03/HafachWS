<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DeviceGroupAutoTarget extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = ['uuid', 'device_group_id', 'target_type'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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

    public function deviceGroup(): BelongsTo
    {
        return $this->belongsTo(DeviceGroup::class, 'device_group_id', 'uuid');
    }
}
