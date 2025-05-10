<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Functionary extends Model
{
    use HasFactory;

    protected $table = 'functionaries';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        'name',
        'cpf',
        'phone',
        'email',
        'birth_date',
        'photo_path',
        'uuid',
        'school_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
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


    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'uuid');
    }

    public function deviceGroups()
    {
        return $this->morphToMany(
            \App\Models\DeviceGroup::class,
            'person',
            'device_group_person',
            'person_id',
            'device_group_id'
        )->withPivot('person_type')->withTimestamps();
    }

    public function externalDeviceIds()
    {
        return $this->morphMany(\App\Models\ExternalDeviceId::class, 'person');
    }
}
