<?php

namespace App\Repositories\v1\Interface\Author\Category;

interface ICategory
{
    public function get($request,$limit);
    public function store($model);
    public function show($model);
    public function update($model);
    public function delete($model);

}
