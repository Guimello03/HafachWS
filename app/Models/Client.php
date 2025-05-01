<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
        protected $fillable = [
        'name',
        'email',
        'cnpj',
        ];

        public function schools()
        {
            return $this->hasMany(School::class);
        }
        protected $casts = [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

        public function users()
{
    return $this->hasMany(User::class);
}
}
