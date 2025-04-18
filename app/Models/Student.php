<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    ];

}
