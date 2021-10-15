<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogSpecificationRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalog_id',
        'specification_id',
        'value'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
