<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'short_description',
        'image_preview',
        'manufacturer_id',
        'price',
        'price_old',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
