<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public static function getSpecificationsByCatalog($catalogId) {
        $specifications = self::pluck('name', 'id');

        $catalogSpecificationRelation = CatalogSpecificationRelation::where(['catalog_id' => $catalogId])->get()->toArray();
        foreach ($catalogSpecificationRelation as $id => $item) {
            $catalogSpecificationRelation[$id]['specification_name'] = $specifications[$item['specification_id']];
        }

        return $catalogSpecificationRelation;
    }
}
