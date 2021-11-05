<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Category;
use Inertia\Inertia;
use \Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class CategoryController extends Controller
{

    public static function menu()
    {
        $categories = Category::all();
        $data = [];
        foreach ($categories as $category) {
            if ($category['parent_id'] !== null) {
                $data[$category['parent_id']]['children'][] = [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                ];
            } else {
                $data[$category['id']] = [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'children' => []
                ];
            }
        }

        return $data;
    }

    public static function view($slug, $slugChild = null)
    {
        // Если это категория певого уровня
        if ($slugChild === null) {
            $getCategory = Category::where(['slug' => $slug])->first();
            if ($getCategory) {
                $categoriesChild = Category::where(['parent_id' => $getCategory->id])->get();
                $catalog = Catalog::getCatalog(null, $getCategory->id);
                foreach ($categoriesChild as $value) {
                    $catalogChild = Catalog::getCatalog(null, $value->id);
                    $catalog = array_merge($catalog, $catalogChild);
                }

                return Inertia::render('Web/Category', [
                    'category' => $getCategory,
                    'categoriesChild' => $categoriesChild,
                    'catalog' => $catalog
                ]);
            } else {
                return abort(404);
            }
        }

        // Если это категория второго уровня или продукция
        $getCategory = Category::where(['slug' => $slugChild])->first();
        if (!$getCategory) {
            $getCatalog = Catalog::getItem($slugChild);
            if (!$getCatalog) {
                return abort(404);
            }

            return Inertia::render('Web/Catalog', [
                'catalog' => $getCatalog,
                'category' => Category::where(['slug' => $slug])->first(),
                'categoryChild' => null,
            ]);
        }

        return Inertia::render('Web/Category', [
            'category' => $getCategory,
            'categoriesChild' => null,
            'catalog' => Catalog::where(['parent_id' => $getCategory->id])
        ]);
    }
}
