<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalDeviceId extends Model
{
    protected $fillable = [
        'person_id',
        'person_type',
        'device_id',
        'external_id',
    ];

    protected $casts = [
        'external_id' => 'integer',
    ];

    /**
     * Pessoa associada (Aluno, Funcionário, Responsável)
     */
    public function person(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Dispositivo ao qual o external_id pertence
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
