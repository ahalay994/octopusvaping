<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * All news.
     *
     * @return mixed
     */
    public function store()
    {
        return Inertia::render('Admin/Categories/View', [
            'data' => self::get()
        ]);
    }

    public function get() {
        $categories = Category::get();
        $data = [];
        foreach ($categories as $category) {
            $parentCategory = Category::where(['id' => $category->parent_id])->first();
            array_push($data, [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'deep' => $category->deep,
                'parent_id' => $parentCategory ? $parentCategory->name : null,
            ]);
        }

        return $data;
    }

    public function addView() {
        return Inertia::render('Admin/Categories/Add');
    }

    public function editView($id) {
        $category = Category::where(['id' => $id])->first();

        return Inertia::render('Admin/Categories/Edit', [
            'data' => $category
        ]);
    }

    /**
     * Get all categories.
     *
     * @return mixed
     */
    public function getAll()
    {
        return Category::pluck('name', 'id');
    }

    public function getAllEdit($id)
    {
        $categoriesAll = Category::get();
        $parentIds = $categoriesAll->pluck('id', 'parent_id');
        $categories = $categoriesAll->pluck('name', 'id');

        // Убираем текущую категорию
        $categories->forget($id);

        // Убираем те категории, для которых данная категория родительская
        $childId = $parentIds->get($id);
        if ($childId) {
            $categories->forget($childId);
            $childChild = $parentIds->get($childId);

            if ($childChild) $categories->forget($childChild);
        }

        return $categories;
    }

    /**
     * Add new category.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|string[]
     */
    public function add(\Illuminate\Http\Request $request) {
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id ?: null,
            'deep' => $request->deep ?: 0
        ]);

        return redirect()->route('admin.category.view')->with('status', 'Категория добавлена');
    }

    /**
     * Edit category.
     *
     * @return
     */
    public function edit(\Illuminate\Http\Request $request) {
        Category::where('id', $request->id)
            ->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'parent_id' => $request->parent_id ?: null,
                'deep' => $request->deep ?: 0
        ]);

        return redirect()->route('admin.category.view')->with('status', 'Категория обновлена');
    }

    /**
     * Delete.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id) {
        try {
            return Category::where(['id' => $id])->delete();
        } catch (\Illuminate\Database\QueryException $exception) {
            return $exception->errorInfo;
        }
    }
}
