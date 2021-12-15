<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\CatalogSpecificationRelation;
use App\Models\Category;
use Illuminate\Support\Str;
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
                $catalog = Catalog::getCatalog($getCategory->id);
                foreach ($categoriesChild as $value) {
                    $catalogChild = Catalog::getCatalog($value->id);
                    $catalog = array_merge($catalog, $catalogChild);
                }

                return Inertia::render('Web/Category', [
                    'category' => $getCategory,
                    'categoriesChild' => $categoriesChild,
                    'catalog' => $catalog,
                    'filter' => self::generateSpecifications($catalog)
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

        $catalog = Catalog::where(['parent_id' => $getCategory->id])->get();
        return Inertia::render('Web/Category', [
            'category' => $getCategory,
            'categoriesChild' => null,
            'catalog' => $catalog,
            'filter' => self::generateSpecifications($catalog->toArray()),
        ]);
    }

    static function generateSpecifications($catalog) {
        $catalogIds = array_column($catalog, 'id');
        $catalogSpecificationRelation = CatalogSpecificationRelation::whereIn('catalog_id', $catalogIds)
            ->leftJoin('specifications', 'specifications.id', '=', 'catalog_specification_relations.specification_id')
            ->get();

        $filter = [];
        foreach ($catalogSpecificationRelation as $item) {
            if (!isset($filter[$item->specification_id])) {
                $filter[$item->specification_id] = [];
            }

            if (!in_array($item->value, $filter[$item->specification_id])) {
                $filter[$item->specification_id][] = $item->value;
            }
        }

        return $filter;
    }

    public static function filter() {
        $data = [];

        $urlComponents = parse_url($_GET['url']);
        $path = explode('/', substr($urlComponents['path'], 1));

        if (isset($urlComponents['query'])) {
            parse_str($urlComponents['query'], $params);

            foreach ($params as $name => $param) {
                $data[$name] = explode(',', $param);
            }
        }

        $categoryParent = Category::where(['slug' => $path[0]])->first();
        // Если есть дочерняя категория
        if (isset($path[1]) && $path[1] !== '') {
            $categoryChild = Category::where(['slug' => $path[1]])->get();
            // получаем данные только этой категории
            return Catalog::getCatalog($categoryChild->id, $data);
        }

        $catalog = Catalog::getCatalog($categoryParent->id, $data);
        $categoriesChild = Category::where(['parent_id' => $categoryParent->id])->get();
        foreach ($categoriesChild as $value) {
            dd($value);
            $catalogChild = Catalog::getCatalog($value->id, $data);
            $catalog = array_merge($catalog, $catalogChild);
        }

        return $catalog;
    }
}
