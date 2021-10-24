<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\CatalogSpecificationRelation;
use App\Models\Category;
use App\Models\Image;
use App\Models\Manufacturer;
use App\Models\Meta;
use App\Models\News;
use App\Models\Specification;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CatalogController extends Controller
{
    const FILE_PATH = 'images/catalog/';
    const TYPE_MODEL = 'catalog';

    public function get() {
        $catalog = Catalog::all();
        $categories = (new CategoryController)->getNames();
        $manufacturers = (new ManufacturerController())->getNames();
        $data = [];
        foreach ($catalog as $item) {
            $categoryId = $item->category_id;
            $category = array_filter($categories, function ($elem) use ($categoryId) {
                return array_key_exists('value', $elem) and $elem['value'] === $categoryId;
            });
            $categoryLabel = '';
            foreach ($category as $i) $categoryLabel = $i['label'];
            $manufacturerId = $item->manufacturer_id;
            $manufacturer = array_filter($manufacturers, function ($elem) use ($manufacturerId) {
                return array_key_exists('value', $elem) and $elem['value'] === $manufacturerId;
            });
            $manufacturerLabel = '';
            foreach ($manufacturer as $i) $manufacturerLabel = $i['label'];

            array_push($data, [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'category_id' => $categoryLabel,
                'description' => $item->description,
                'short_description' => $item->short_description,
                'images' => Image::where(['model_type' => self::TYPE_MODEL, 'model_id' => $item->id])->get(),
                'image_preview' => $item->image_preview,
                'manufacturer_id' => $manufacturer ? $manufacturerLabel : '',
                'price' => $item->price,
                'price_old' => $item->price_old,
                'visible' => $item->visible,
            ]);
        }

        return $data;
    }

    /**
     * All.
     *
     * @return mixed
     */
    public function store()
    {
        return Inertia::render('Admin/Catalog/View', [
            'data' => self::get()
        ]);
    }

    public function addView() {
        return Inertia::render('Admin/Catalog/Add');
    }

    public function editView($id) {
        $catalog = Catalog::where(['id' => $id])->first();
        $images = Image::select(['name'])->where(['model_id' => $id, 'model_type' => self::TYPE_MODEL])->get();
        $meta = Meta::where(['model_type' => self::TYPE_MODEL, 'model_id' => $id])->first();

        $specifications = Specification::select(['specifications.id as name', 'catalog_specification_relations.value as value'])
            ->where(['catalog_specification_relations.catalog_id' => $id])
            ->leftJoin('catalog_specification_relations', 'catalog_specification_relations.specification_id', '=', 'specifications.id')
            ->get();

        return Inertia::render('Admin/Catalog/Edit', [
            'data' => [
                'id' => $catalog->id,
                'name' => $catalog->name,
                'short_description' => $catalog->short_description,
                'description' => $catalog->description,
                'images' => [],
                'images_url' => $images,
                'image_preview' => [],
                'image_preview_url' => [$catalog->image_preview],
                'price' => $catalog->price,
                'price_old' => $catalog->price_old,
                'meta_title' => $meta->title,
                'meta_description' => $meta->description,
                'meta_image' => [],
                'meta_image_url' => [$meta->image],
                'category' => $catalog->category_id,
                'manufacturer' => $catalog->manufacturer_id,
                'specifications' => $specifications,
                'dir_image' => self::FILE_PATH,
                'visible' => $catalog->visible,
            ]
        ]);
    }

    /**
     * Get all.
     *
     * @return mixed
     */
    public function getNames()
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
            'image_preview' => !!$images['image_preview'] ? $images['image_preview'][0] : $images['images'][0],
            'manufacturer_id' => $request['manufacturer'],
            'price' => $request['price'],
            'price_old' => $request['price_old'],
            'visible' => $request['visible'] === true || intval($request['visible']) === 1 ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);

        // Характеристики
        if ($request->specifications && count($request->specifications) > 0) {
            foreach ($request->specifications as $specification) {
                CatalogSpecificationRelation::create([
                    'catalog_id' => $catalog->id,
                    'specification_id' => intval($specification['name']),
                    'value' => $specification['value'],
                    'created_at' => date('Y-m-d H:i:s', time()),
                ]);
            }
        }

        // Изображения
        if ($catalog && $images['images']) {
            foreach ($images['images'] as $image) {
                Image::create([
                    'model_type' => self::TYPE_MODEL,
                    'model_id' => $catalog['id'],
                    'name' => $image,
                    'created_at' => date('Y-m-d H:i:s', time()),
                ]);
            }
        }

        // Мета теги
        Meta::create([
            'model_type' => self::TYPE_MODEL,
            'model_id' => $catalog->id,
            'title' => $request->meta_title ?: $catalog->name,
            'description' => $request->meta_description ?: $catalog->description,
            'image' => $images['meta_image'] ? $images['meta_image'][0] : ($images['image_preview'] ? $images['image_preview'][0] : ($images['images'] ? $images['images'][0] : null)),
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);

        return redirect()->route('admin.catalog.view')->with('status', 'Запись добавлена');
    }

    /**
     * Edit news.
     *
     * @return
     */
    public function edit(\Illuminate\Http\Request $request) {
        $images = uploadImages($request, self::FILE_PATH);

        if (!$images) {
            return [
                'error' => 'Ошибка с загрузкой изображения'
            ];
        }
        // Сам товар
        $catalog = Catalog::where(['id' => $request['id']])->first();
        $catalog->update([
            'name' => $request['name'],
            'slug' => Str::slug($request['name']),
            'category_id' => $request['category'],
            'short_description' => $request['short_description'],
            'description' => $request['description'],
            'image_preview' => gettype($images) === 'boolean' || !isset($images['image_preview']) ? $catalog['image_preview'] : (!!$images['image_preview'] ? $images['image_preview'][0] : $catalog['image_preview']),
            'manufacturer_id' => $request['manufacturer'],
            'price' => $request['price'],
            'price_old' => $request['price_old'],
            'visible' => $request['visible'] === true || intval($request['visible']) === 1 ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        // Характеристики
        $catalogSpecificationRelation = CatalogSpecificationRelation::where(['catalog_id' => $catalog->id])->pluck('id', 'specification_id');
        if ($request->specifications && count($request->specifications) > 0) {
            foreach ($request->specifications as $specification) {
                if (isset($catalogSpecificationRelation[intval($specification['name'])])) {
                    unset($catalogSpecificationRelation[intval($specification['name'])]);
                    continue;
                }
                CatalogSpecificationRelation::create([
                    'catalog_id' => $catalog->id,
                    'specification_id' => intval($specification['name']),
                    'value' => $specification['value'],
                    'created_at' => date('Y-m-d H:i:s', time()),
                ]);
            }
        }
        if (count($catalogSpecificationRelation) > 0) {
            foreach ($catalogSpecificationRelation as $id) {
                CatalogSpecificationRelation::where(['id' => $id])->delete();
            }
        }

        // Изображения
        $imagesDB = Image::where(['model_type' => self::TYPE_MODEL, 'model_id' => $catalog['id']])->pluck('id', 'name');
        foreach ($request->images_url as $item) {
            if (gettype($item) === 'array' && isset($item['name'])) {
                unset($imagesDB[$item['name']]);
            }
        }
        foreach ($imagesDB as $name => $id) {
            Image::where(['id' => $id])->delete();
        }

        if (gettype($images) !== 'boolean' && isset($images['images'])) {
            foreach ($images['images'] as $image) {
                Image::create([
                    'model_type' => self::TYPE_MODEL,
                    'model_id' => $catalog['id'],
                    'name' => $image,
                    'created_at' => date('Y-m-d H:i:s', time()),
                ]);
            }
        }

        // Мета теги
        $meta = Meta::where(['model_id' => $catalog->id, 'model_type' => self::TYPE_MODEL])->first();
        $meta->update([
            'title' => $request->meta_title ?: $catalog->name,
            'description' => $request->meta_description ?: $catalog->description,
            'image' => gettype($images) === 'boolean' || !isset($images['meta_image']) ? $meta->image : ($images['meta_image'] ? $images['meta_image'][0] : $meta->image),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        return redirect()->route('admin.catalog.view')->with('status', 'Запись обновлена');
    }

    /**
     * Delete news.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        try {
            $catalogSpecificationRelation = CatalogSpecificationRelation::where(['catalog_id' => $id])->get();
            foreach ($catalogSpecificationRelation as $item) {
                $item->delete();
            }
            $images = Image::where(['model_id' => $id, 'model_type' => CatalogController::TYPE_MODEL])->get();
            foreach ($images as $image) {
                $image->delete();
            }
            Meta::where(['model_id' => $id, 'model_type' => CatalogController::TYPE_MODEL])->delete();
            return Catalog::where(['id' => $id])->delete();
        } catch (\Illuminate\Database\QueryException $exception) {
            return $exception->errorInfo;
        }
    }
}
