<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class guardian extends Model
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
    ];
}
