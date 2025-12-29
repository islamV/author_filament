<?php

namespace App\Http\Resources\v1\Author\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavBookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->favoritable->id,
            'title' => $this->favoritable->title,
            'author_id' => $this->favoritable->user->id ?? null,
            'author_name' => $this->favoritable->user->first_name . ' ' . $this->favoritable->user->last_name ?? null,
            'description' => $this->favoritable->description,
            'image' => $this->favoritable->image 
            ? asset('storage/' . $this->favoritable->image) 
            : null,

            'favourite' => 1,
            'views' => $this->favoritable->viewsCount(),
            'average_rating' => $this->favoritable->getAverageRatingAttribute(),
        ];
    }
}
