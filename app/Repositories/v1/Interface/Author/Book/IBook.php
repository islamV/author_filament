<?php

namespace App\Repositories\v1\Interface\Author\Book;

interface IBook
{
    public function get($request,$limit);
    public function show($model);
    public function getAuthorBooks($published,$limit = 10);
    public function getAuthorBook($book,$userId);
    public function store($model);
    public function update($model);
    public function delete($model);
    public function rate($model,$score, $comment = null);
    public function getRate($model,$limit);

    public function getBookByCategory($model,$limit);

}
