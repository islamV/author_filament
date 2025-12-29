<?php

namespace App\Services\v1\Dashboard\Ad;

use App\Repositories\v1\Interface\Dashboard\Ad\IAd;

class AdService
{
    protected IAd $ad;

    public function __construct(IAd $ad)
    {
        $this->ad = $ad;
    }

    public function index($request,$limit)
    {
        $query = $request->input('query');
        return $this->ad->get($query,$limit);
    }

    public function store($request)
    {
        $ad = [
            'title' => $request->title,
        ];
        return $this->ad->store($ad);
    }

    public function show($ad)
    {
        return $this->ad->show($ad);
    }

    public function update($request, $ad)
    {
        $ad->title = $request->title ?? $ad->title;

        return $this->ad->update($ad);
    }

    public function delete($ad)
    {
        return $this->ad->delete($ad);
    }
}
