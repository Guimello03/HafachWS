<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DeviceGroup extends Model
{
    use HasFactory;

    protected $table = 'device_groups';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['uuid', 'school_id', 'name'];

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

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'uuid');
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
        return $this->hasMany(DeviceGroupAutoTarget::class, 'device_group_id', 'uuid');
    }

    public function devices(): BelongsToMany
    {
        return $this->belongsToMany(Device::class, 'device_device_group', 'device_group_id', 'device_id');
    }

    public function allPeople(): \Illuminate\Support\Collection
    {
        return collect()
            ->merge($this->students)
            ->merge($this->guardians)
            ->merge($this->functionaries);
    }
    public function commands()
{
    return $this->hasMany(\App\Models\DeviceGroupCommand::class, 'device_group_id', 'uuid');
}
}
