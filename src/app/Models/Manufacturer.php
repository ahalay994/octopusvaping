<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'image'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public static function images() {
        return self::pluck('image')->toArray();
    }
}
