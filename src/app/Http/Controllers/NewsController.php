<?php

namespace App\Http\Controllers;

use App\Models\News;
use Inertia\Inertia;

class NewsController extends Controller
{
    const FILE_PATH = 'images/news/';
    private $errors = [];

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
            'image' => '/' . self::FILE_PATH . $news['image'],
            'image_preview' => '/' . self::FILE_PATH . $news['image_preview'],
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
    public function getAll()
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

        $dataSave = [
            'title' => $request->title,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'updated_at' => date('Y-m-d H:i:s', time())
        ];

        if (gettype($images) !== 'boolean') {
            if (isset($images['image'])) $dataSave['image'] = $images['image'];
            if (isset($images['image_preview'])) $dataSave['image_preview'] = $images['image_preview'];
        }

        if (!$images) {
            return [
                'error' => 'Ошибка с загрузкой изображения'
            ];
        }
        News::where('id', $request->id)->update($dataSave);

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

    function validateFields($request) {
        $errors = [];
        $fields = [
            'title' => 'Заголовок',
            'short_description' => 'Краткое описание',
            'description' => 'Описание',
            'image' => 'Изображение',
            'image_preview' => 'Превью-изображение',
        ];

        foreach ($fields as $field => $name) {
            if ($request[$field] === null) {
                $errors[$field] = `Поле "${name}" обязатель для заполнения`;
            }
        }

        return $errors;
    }
}
