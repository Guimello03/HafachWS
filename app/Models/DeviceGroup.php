<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DeviceGroup extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'school_id', 'name', 'type'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function allPeople()
    {
        return $this->morphToMany(
            Model::class,
            'person',
            'device_group_person',
            'device_group_id',
            'person_id'
        )->withPivot('person_type')->withTimestamps();
    }

    public function students()
    {
        return $this->morphedByMany(
            \App\Models\Student::class,
            'person',
            'device_group_person',
            'device_group_id',
            'person_id'
        )->withPivot('person_type')->withTimestamps();
    }

    public function guardians()
    {
        return $this->morphedByMany(
            \App\Models\Guardian::class,
            'person',
            'device_group_person',
            'device_group_id',
            'person_id'
        )->withPivot('person_type')->withTimestamps();
    }

    public function functionaries()
    {
        return $this->morphedByMany(
            \App\Models\Functionary::class,
            'person',
            'device_group_person',
            'device_group_id',
            'person_id'
        )->withPivot('person_type')->withTimestamps();
    }

    

    public function autoTargets(): HasMany
    {
        return $this->hasMany(DeviceGroupAutoTarget::class);
    }

    public function devices(): BelongsToMany
{
    return $this->belongsToMany(Device::class, 'device_device_group');
}
}
