<?php

namespace App\Http\Resources\v1\Author\Book;

use App\Http\Resources\v1\Dashboard\Ad\AdResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'most_popular' => HomePageBooksResource::collection($this['most_popular']->items()),
            'featured' => HomePageBooksResource::collection($this['featured']->items()),
            'ads' => AdResource::collection($this['ads']->items()),
        ];
    }
}
