<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceStatus extends Model
{
    protected $table = 'device_status'; // Corrige o nome da tabela para o singular

    protected $primaryKey = 'device_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'device_id',
        'last_seen',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    public function getIsOnlineAttribute(): bool
    {
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
    }
}
