<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DeviceEvent extends Model
{
    protected $fillable = [
        'person_id',
        'person_type',
        'device_id',
        'date',
        'direction',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Retorna a pessoa relacionada (Student, Guardian, Functionary)
     */
    public function person(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Dispositivo associado ao evento
     */
    public function device()
    {
        return $this->belongsTo(\App\Models\Device::class, 'device_id', 'uuid');
    }
}
