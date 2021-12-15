<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Category;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public static function view($slug, $slugChild, $slugCatalog)
    {
        return Inertia::render('Web/Catalog', [
            'catalog' => Catalog::where(['slug' => $slugCatalog])->first(),
            'category' => Category::where(['slug' => $slug])->first(),
            'categoryChild' => Category::where(['slug' => $slugChild])->first(),
        ]);
    }
}
