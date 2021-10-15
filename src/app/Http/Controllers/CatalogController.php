<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\CatalogSpecificationRelation;
use App\Models\Category;
use App\Models\Image;
use App\Models\Meta;
use App\Models\News;
use App\Models\Specification;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CatalogController extends Controller
{
    const FILE_PATH = 'images/catalog/';
    const TYPE_MODEL = 'catalog';

    /**
     * All.
     *
     * @return mixed
     */
    public function store()
    {
        $catalog = Catalog::all();
        $categories = (new CategoryController)->getAll();
        $data = [];
        foreach ($catalog as $item) {
            array_push($data, [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'category_id' => $categories[$item->category_id],
                'description' => $item->description,
                'short_description' => $item->short_description,
                'images' => Image::where(['model_type' => self::TYPE_MODEL, 'model_id' => $item->id])->get(),
                'preview_image' => $item->preview_image,
                'manufacturer_id' => $item->manufacturer_id,
                'price' => $item->price,
                'price_old' => $item->price_old,
            ]);
        }
        return Inertia::render('Admin/Catalog/View', [
            'data' => $data
        ]);
    }

    public function addView() {
        return Inertia::render('Admin/Catalog/Add');
    }

    public function editView($id) {
        $catalog = Catalog::where(['id' => $id])->first();
        $images = Image::select(['name'])->where(['model_id' => $id, 'model_type' => self::TYPE_MODEL])->get();
        $meta = Meta::select(['title', 'description', 'image'])->where(['model_type' => self::TYPE_MODEL, 'model_id' => $id])->first();
        $specifications = Specification::select(['specifications.id as name', 'catalog_specification_relations.value as value'])
            ->where(['catalog_specification_relations.catalog_id' => $id])
            ->leftJoin('catalog_specification_relations', 'catalog_specification_relations.specification_id', '=', 'specifications.id')
            ->get();

        return Inertia::render('Admin/Catalog/Edit', [
            'data' => [
                'catalog' => $catalog,
                'meta' => $meta,
                'specifications' => $specifications,
                'images' => $images
            ]
        ]);
    }

    /**
     * Get all news.
     *
     * @return mixed
     */
    public function getAll()
    {
        return Category::pluck('name', 'id');
    }

    /**
     * Add new news.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|string[]
     */
    public function add(\Illuminate\Http\Request $request) {
        $images = uploadImages($request, self::FILE_PATH);
        if (!$images) {
            return [
                'error' => 'Ошибка с загрузкой изображения'
            ];
        }
        // Сам товар
        $catalog = Catalog::create([
            'name' => $request['name'],
            'slug' => Str::slug($request['name']),
            'category_id' => $request['category'],
            'short_description' => $request['short_description'],
            'description' => $request['description'],
            'image' => null,
            'preview_image' => !!$images['preview_image'] ? $images['preview_image'][0] : $images['images'][0],
            'manufacturer_id' => $request['manufacturer_id'],
            'price' => $request['price'],
            'price_old' => $request['price_old'],
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);

        // Характеристики
        if ($request->specifications && count($request->specifications) > 0) {
            foreach ($request->specifications as $specification) {
                CatalogSpecificationRelation::create([
                    'catalog_id' => $catalog->id,
                    'specification_id' => intval($specification['name']),
                    'value' => $specification['value']
                ]);
            }
        }

        // Изображения
        if ($catalog && $images['images']) {
            foreach ($images['images'] as $image) {
                Image::create([
                    'model_type' => self::TYPE_MODEL,
                    'model_id' => $catalog['id'],
                    'name' => $image
                ]);
            }
        }

        // Мета теги
        Meta::create([
            'model_type' => self::TYPE_MODEL,
            'model_id' => $catalog->id,
            'title' => $request->meta_title ?: $catalog->name,
            'description' => $request->meta_description ?: $catalog->description,
            'image' => $images['meta_image'] ? $images['meta_image'][0] : ($images['preview_image'] ? $images['preview_image'][0] : ($images['images'] ? $images['images'][0] : null))
        ]);

        return redirect()->route('admin.catalog.view')->with('status', 'Запись добавлена');
    }

    /**
     * Edit news.
     *
     * @return
     */
    public function edit(\Illuminate\Http\Request $request) {
        $images = uploadImage($request, self::FILE_PATH);

        if (!$images) {
            return [
                'error' => 'Ошибка с загрузкой изображения'
            ];
        }
        News::where('id', $request->id)
            ->update([
                'title' => $request->title,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'image' => $images === true ? $request->image : $images['image'],
                'preview_image' => $images === true ? $request->preview_image : $images['preview_image'],
                'manufacturer_id' => $request['manufacturer_id'],
                'price' => $request['price'],
                'price_old' => $request['price_old'],
                'updated_at' => date('Y-m-d H:i:s', time())
        ]);

        return redirect()->route('admin.news.view')->with('status', 'Запись добавлена');
    }

    /**
     * Delete news.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        try {
            return News::where(['id' => $id])->delete();
        } catch (\Illuminate\Database\QueryException $exception) {
            return $exception->errorInfo;
        }
    }
}
