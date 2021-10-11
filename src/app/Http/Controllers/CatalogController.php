<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use http\Env\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use phpDocumentor\Reflection\Types\Boolean;

class CatalogController extends Controller
{
    const FILE_PATH = 'images/news/';

    /**
     * All news.
     *
     * @return mixed
     */
    public function store()
    {
        $news = News::all();
        return Inertia::render('Admin/News/View', [
            'data' => $news
        ]);
    }

    public function addView() {
        return Inertia::render('Admin/News/Add');
    }

    public function editView($id) {
        $news = News::where(['id' => $id])->first();

        return Inertia::render('Admin/News/Edit', [
            'data' => $news
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
        $images = uploadImage($request, self::FILE_PATH);

        if (!$images) {
            return [
                'error' => 'Ошибка с загрузкой изображения'
            ];
        }
        $news = News::create([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => $images === true ? null : $images['image'],
            'image_preview' => $images === true ? null : $images['image_preview'],
        ]);

        return redirect()->route('admin.news.view')->with('status', 'Запись добавлена');
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
                'image_preview' => $images === true ? $request->image_preview : $images['image_preview'],
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
