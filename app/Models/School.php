<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{

    protected $fillable = [
        'name',
        'cnpj',
        'client_id',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
