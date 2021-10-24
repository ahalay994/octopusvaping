<?php

namespace App\Http\Controllers;

use App\Models\News;
use Inertia\Inertia;

class NewsController extends Controller
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
        $data = [
            'id' => $news['id'],
            'title' => $news['title'],
            'short_description' => $news['short_description'],
            'description' => $news['description'],
            'image' => [],
            'image_url' => $news['image'] ? [$news['image']] : [],
            'image_preview' => [],
            'image_preview_url' => $news['image_preview'] ? [$news['image_preview']] : [],
            'visible' => $news['visible'],
            'dir_image' => self::FILE_PATH,
        ];

        return Inertia::render('Admin/News/Edit', [
            'data' => $data
        ]);
    }

    /**
     * Get all news.
     *
     * @return mixed
     */
    public function get()
    {
        return News::all();
    }

    /**
     * Add new news.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array[]|\Illuminate\Http\RedirectResponse|string[]
     */
    public function add(\Illuminate\Http\Request $request) {
        $images = uploadImages($request, self::FILE_PATH);

        if (!$images) {
            return [
                'error' => 'Ошибка с загрузкой изображения'
            ];
        }

        News::create([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => $images === true ? null : $images['image'][0],
            'image_preview' => $images === true ? null : $images['image_preview'][0],
            'visible' => $request->visible === true || intval($request->visible) === 1 ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);

        return redirect()->route('admin.news.view')->with('status', 'Запись добавлена');
    }

    /**
     * Edit news.
     *
     * @return
     */
    public function edit(\Illuminate\Http\Request $request) {
        $images = uploadImages($request, self::FILE_PATH);

        if (!$images) {
            return ['error' => 'Ошибка с загрузкой изображения'];
        }

        $news = News::where('id', $request->id)->first();
        News::where('id', $request->id)->update([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => gettype($images) === 'boolean' || !isset($images['image']) ? $news['image'] : $images['image'][0],
            'image_preview' => gettype($images) === 'boolean' || !isset($images['image_preview']) ? $news['image_preview'] : $images['image_preview'][0],
            'visible' => $request->visible === true || intval($request->visible) === 1 ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s', time()),
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
