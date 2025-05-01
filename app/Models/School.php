<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model

{ use HasFactory;

    protected $fillable = [
        'name',
        'cnpj',
        'client_id',
        'uuid',
    ];
    protected static function booted()
    {
        static::creating(function ($school) {
            $school->uuid = \Str::uuid();
        });
    }
    public function getRouteKeyName()
{
    return 'uuid';
}
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
        return $this->belongsToMany(User::class, 'school_user');
    }
}

