<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'lat',
        'lon',
        'work',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public static function get() {
        $data = [];
        foreach (self::all() as $item) {
            $data[$item->id] = $item;
        }

        return $data;
    }
}
