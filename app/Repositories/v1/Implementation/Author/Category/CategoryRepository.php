<?php

namespace App\Repositories\v1\Implementation\Author\Category;

use App\Repositories\v1\Interface\Author\Category\ICategory;
use App\Models\Category;

class CategoryRepository implements ICategory
{

    public function get($request,$limit = 10)
    {
        return Category::with("books")->whereAny(['name'], 'LIKE', "%{$request}%")->paginate($limit);
    }

    public function store($model)
    {
        return Category::create($model);
    }

    public function show($model)
    {
        return Category::findOrFail($model->id);
    }

    public function update($model)
    {
        return $model->save();
    }

    public function delete($model)
    {
        return $model->delete();
    }

}
