<?php

namespace App\Repositories\v1\Interface\Author\Book;

interface IBookPage
{
    public function store($model);
    public function update($model);
    public function delete($model);

}
