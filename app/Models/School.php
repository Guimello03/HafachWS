<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cnpj',
        'client_id',
        'uuid',
    ];

    protected static function booted()
    {
        static::creating(function ($school) {
            $school->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $keyType = 'string'; // ✅
    public $incrementing = false;  // ✅

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'school_user',   // nome da tabela pivot
            'school_id',     // chave local na pivot (UUID da school)
            'user_id',       // chave do user na pivot
            'uuid',          // PK da school
            'id'             // PK do user
        );
    }
    public function deviceGroups()
{
    return $this->hasMany(DeviceGroup::class);
}

public function devices()
{
    return $this->hasManyThrough(Device::class, DeviceGroup::class);
}
}