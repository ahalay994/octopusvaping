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
        'visible',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public static function getCatalog($limit = null, $categoryId = null) {
        $categories = Category::all();
        $manufacturers = Manufacturer::pluck('name', 'id');
        $categoriesParents = $categories->pluck('parent_id', 'id');
        $categoriesSlugs = $categories->pluck('slug', 'id');

        $data = self::where(['visible' => 1])->orderBy('created_at', 'DESC');

        if ($categoryId !== null) {
            $data = $data->where(['category_id' => $categoryId]);
        }

        if ($limit !== null) {
            $data = $data->limit($limit);
        }

        $data = $data->get()->toArray();

        foreach ($data as $id => $item) {
            $data[$id]['category_slug'] = $categoriesSlugs[$item['category_id']];
            if ($categoriesParents[$item['category_id']] !== null) {
                $data[$id]['category_parent_slug'] = $categoriesSlugs[$categoriesParents[$item['category_id']]];
            }

            $data[$id]['manufacturer'] = $manufacturers[$item['manufacturer_id']];
            unset($data[$id]['manufacturer_id']);
        }
        return $data;
    }

    public static function getItem($slug) {
        $catalog = self::where(['slug' => $slug])->first();

        if (!$catalog) return false;

        $catalog = $catalog->toArray();
        $specifications = Specification::getSpecificationsByCatalog($catalog['id']);
        $catalog['specifications'] = $specifications;

        $images = Image::where(['model_id' => $catalog['id'], 'model_type' => \App\Http\Controllers\CatalogController::TYPE_MODEL])->get()->toArray();
        $catalog['images'] = $images;
        $catalog['image_path'] = \App\Http\Controllers\CatalogController::FILE_PATH;

        $manufacturers = Manufacturer::pluck('name', 'id');
        $catalog['manufacturer'] = $manufacturers[$catalog['manufacturer_id']];

        return $catalog;
    }
}
