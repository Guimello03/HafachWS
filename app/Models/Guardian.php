<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guardian extends Model
{
    use HasFactory;
    protected $table = 'guardians';
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
        static::creating(function ($guardian) {
            $guardian->uuid = (string) \Illuminate\Support\Str::uuid();
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
public function students()
{
    return $this->hasMany(Student::class);
}

}
