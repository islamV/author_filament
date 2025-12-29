<?php

namespace App\Http\Controllers\v1\Author\Ad;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Dashboard\Ad\AdResource;
use App\Models\{Ad};
use App\Services\v1\Dashboard\Ad\AdService;
use Illuminate\Http\Request;

class AdController extends Controller
{
    private AdService $adService;
    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }
    public function index(Request $request)
    {
        return $this->returnData(__('messages.ad.list'),200,
            AdResource::collection($this->adService->index($request,$request->get('per_page',10))));
    }
    public function show(Ad $ad)
    {
        return $this->returnData(__('messages.ad.details'),200,
            new AdResource($this->adService->show($ad)));
    }
}
