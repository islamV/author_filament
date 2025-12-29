<?php

namespace App\Repositories\v1\Implementation\Dashboard\Ad;

use App\Repositories\v1\Interface\Dashboard\Ad\IAd;
use App\Models\Ad;

class AdRepository implements IAd
{

    public function get($request,$limit = 10)
    {
        return Ad::paginate($limit);
    }

    public function show($model)
    {
        return Ad::findOrFail($model->id);
    }

    public function store($model)
    {
        return Ad::create($model);
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
