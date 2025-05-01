<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Studant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Studant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Studant query()
 * @mixin \Eloquent
 */
class Student extends Model
{
 use HasFactory;
   protected $table = 'students';
    protected $fillable = [
        'name',
        'registration_number',
        'birth_date',
        'photo_path',
        'uuid',
        'guardian_id',
        'school_id',
    ];
    protected static function booted()
{
    static::creating(function ($student) {
        $student->uuid = (string) Str::uuid();
    });
}
public function getRouteKeyName()
{
    return 'uuid';
}
public function guardian()
{
    return $this->belongsTo(Guardian::class, 'guardian_id', 'uuid');

}
protected $casts = [
    'birth_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

}
