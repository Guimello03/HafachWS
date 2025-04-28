<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
        protected $fillable = [
        'name',
        'email',
        'cnpj',
        ];

        public function schools()
        {
            return $this->hasMany(School::class);
        }

        public function users()
{
    return $this->hasMany(User::class);
}
}
