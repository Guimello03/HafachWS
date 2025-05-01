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
    protected static function booted()
    {
        static::creating(function ($functionary) {
            $functionary->uuid = (string) Str::uuid();
        });
    }
    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
