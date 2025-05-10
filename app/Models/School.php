<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;
    protected $primaryKey = 'uuid'; 


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
        return $this->belongsToMany(User::class, 'school_user', 'school_id', 'user_id')->withTimestamps();

    }
    public function deviceGroups()
{
    return $this->hasMany(DeviceGroup::class, 'school_id', 'uuid'); // ✅ Correto!
}


public function devices()
{
    return $this->hasManyThrough(Device::class, DeviceGroup::class);
}
}