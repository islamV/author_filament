<?php

namespace App\Repositories\v1\Implementation\Author\book_parts;

use App\Repositories\v1\Interface\Author\book_parts\Ibook_parts;
use App\Models\book_parts;

class book_partsRepository implements Ibook_parts
{

    public function get($request,$book_id,$limit = 10)
    {
        return book_parts::where('book_id', $book_id)->where('chapter', 'LIKE', "%{$request}%")->paginate($limit);
    }

    public function show($model)
    {
        $model = book_parts::findOrFail($model);
        $model->addView();
        return $model;
    }
    public function find($model)
    {
        return book_parts::findOrFail($model);
    }
    public function store($model)
    {
        return book_parts::create($model);
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
