<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = ['uuid', 'school_id', 'key', 'value'];
    protected $guarded = [];
    protected $primaryKey = 'uuid'; // ✔️ informar que a PK é uuid
    public $incrementing = false; // ✔️ UUID não é autoincrement

    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = (string) \Str::uuid();
        });
    }
}
