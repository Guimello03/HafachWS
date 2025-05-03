<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Functionary extends Model
{
    use HasFactory;

    protected $table = 'functionaries';

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
        static::creating(function ($functionary) {
            $functionary->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $keyType = 'string';
    public $incrementing = false;

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'uuid');
    }
    public function people()
{
    return $this->morphedByMany(
        User::class, // ou qualquer modelo base, pode ser sobrescrito dinamicamente
        'person',
        'device_group_person',
        'device_group_id',
        'person_id'
    )->withPivot('person_type')->withTimestamps();
}
public function externalDeviceIds()
{
    return $this->morphMany(\App\Models\ExternalDeviceId::class, 'person');
}
}
