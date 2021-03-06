<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Inertia\Inertia;
use \Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class ManufacturerController extends Controller
{
    const FILE_PATH = 'images/manufacturer/';

    /**
     * All.
     *
     * @return mixed
     */
    public function store()
    {
        $manufacturers = Manufacturer::all();
        $data = [];
        foreach ($manufacturers as $manufacturer) {
            array_push($data, [
                'id' => $manufacturer->id,
                'name' => $manufacturer->name,
                'image' => $manufacturer->image,
                'state' => 'view',
            ]);
        }
        return Inertia::render('Admin/Manufacturer/View', [
            'data' => $data
        ]);
    }

    /**
     * Get all.
     *
     * @return mixed
     */
    public function get()
    {
        $manufacturers = Manufacturer::all();
        $data = [];
        foreach ($manufacturers as $manufacturer) {
            array_push($data, [
                'id' => $manufacturer->id,
                'name' => $manufacturer->name,
                'image' => $manufacturer->image,
                'state' => 'view',
            ]);
        }
        return $data;
    }

    /**
     * Get all.
     *
     * @return mixed
     */
    public function getNames()
    {
        $manufacturers = Manufacturer::all();
        $data = [];
        foreach ($manufacturers as $manufacturer) {
            array_push($data, [
                'value' => $manufacturer->id,
                'label' => $manufacturer->name
            ]);
        }
        return $data;
    }

    public function save(Request $request) {
        $images = uploadImageTable($request, self::FILE_PATH, 'manufacturers');

        if (!$images) {
            return [
                'error' => 'Ошибка с загрузкой изображения'
            ];
        }

        $manufacturers = $request->manufacturers;
        foreach ($manufacturers as $id => $manufacturer) {
            if ($manufacturer['name'] === null || $manufacturer['name'] === '') continue;
            if ($manufacturer['state'] === 'view') continue;

            if ($manufacturer['id'] === null) {
                Manufacturer::insert([
                    'name' => $manufacturer['name'],
                    'image' => $images['_' . ($id)],
                    'created_at' => date('Y-m-d H:i:s', time()),
                ]);
            } else {
                Manufacturer::where(['id' => $manufacturer['id']])
                    ->update([
                        'name' => $manufacturer['name'],
                        'image' => (gettype($images) !== 'boolean' && count($images) > 0 && $images[$manufacturer['id']])
                            ? $images[$manufacturer['id']]
                            : $manufacturer['image'],
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
            }
        }

        return redirect()->route('admin.manufacturer.view');
    }

    /**
     * Delete.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        try {
            return Manufacturer::where(['id' => $id])->delete();
        } catch (QueryException $exception) {
            return $exception->errorInfo;
        }
    }
}
