<?php

namespace App\Repositories\v1\Implementation\Author\Book;

use App\Models\BookPage;
use App\Repositories\v1\Interface\Author\Book\IBookPage;

class BookPageRepository implements IBookPage
{
    public function store($model)
    {
        return BookPage::create($model);
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
