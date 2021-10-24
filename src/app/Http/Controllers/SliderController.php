<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Inertia\Inertia;
use \Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

class SliderController extends Controller
{
    const FILE_PATH = 'images/slider/';

    public function get()
    {
        $sliders = Slider::orderBy('order', 'DESC')->get();
        $data = [];
        foreach ($sliders as $slider) {
            array_push($data, [
                'id' => $slider->id,
                'image' => $slider->image,
                'order' => $slider->order,
                'visible' => $slider->visible,
                'state' => 'view',
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
        return Inertia::render('Admin/Slider/View', [
            'data' => self::get()
        ]);
    }

    /**
     * Get all.
     *
     * @return mixed
     */
    public function getNames()
    {
        $sliders = Slider::all();
        $data = [];
        foreach ($sliders as $slider) {
            array_push($data, [
                'value' => $slider->id,
                'label' => $slider->image
            ]);
        }
        return $data;
    }

    public function save(Request $request) {
        $images = uploadImageTable($request, self::FILE_PATH, 'sliders');

        if (!$images) {
            return [
                'error' => 'Ошибка с загрузкой изображения'
            ];
        }

        $sliders = $request->sliders;
        foreach ($sliders as $id => $slider) {
            if ($slider['state'] === 'view') continue;
            if ($slider['id'] === null) {
                Slider::insert([
                    'image' => $images['_' . ($id)],
                    'order' => $slider['order'] ?: 0,
                    'visible' => $slider['visible'] === true || intval($slider['visible']) === 1 ? 1 : 0,
                    'created_at' => date('Y-m-d H:i:s', time())
                ]);
            } else {
                Slider::where(['id' => $slider['id']])
                    ->update([
                        'image' => (gettype($images) !== 'boolean' && count($images) > 0 && $images[$slider['id']])
                            ? $images[$slider['id']]
                            : $slider['image'],
                        'order' => $slider['order'] ?: 0,
                        'visible' => $slider['visible'] === true || intval($slider['visible']) === 1 ? 1 : 0,
                        'updated_at' => date('Y-m-d H:i:s', time())
                    ]);
            }

        }

        return redirect()->route('admin.slider.view');
    }

    /**
     * Delete.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        try {
            return Slider::where(['id' => $id])->delete();
        } catch (QueryException $exception) {
            return $exception->errorInfo;
        }
    }
}
