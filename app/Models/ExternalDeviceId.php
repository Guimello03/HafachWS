<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalDeviceId extends Model
{
    protected $primaryKey = 'uuid';      // 👈 informa que a chave primária é uuid
public $incrementing = false;       // 👈 desativa auto-incremento
protected $keyType = 'string';      
    protected $fillable = [
        'uuid',
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


     protected static function booted()
{
    static::creating(function ($model) {
        if (empty($model->uuid)) {
            $model->uuid = (string) Str::uuid();
        }
    });
}
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
