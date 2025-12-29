<?php

namespace App\Repositories\v1\Interface\Author\book_parts;

interface Ibook_parts
{
    public function get($request, $book_id,$limit);
    public function show($model);
    public function find($model);
    public function store($model);
    public function update($model);
    public function delete($model);
}
