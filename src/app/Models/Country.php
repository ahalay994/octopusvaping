<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public static function getName() {
        return self::pluck('name', 'id');
    }
}
