<?php

namespace App\Http\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(Category $category)
    {
        return [
            'id'   => (int) $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ];
    }
}
