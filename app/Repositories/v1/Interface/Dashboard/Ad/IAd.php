<?php

namespace App\Repositories\v1\Interface\Dashboard\Ad;

interface IAd
{
    public function get($request,$limit);
    public function show($model);
    public function store($model);
    public function update($model);
    public function delete($model);

}