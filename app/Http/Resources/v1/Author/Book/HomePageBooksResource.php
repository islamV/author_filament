<?php

namespace App\Http\Resources\v1\Author\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageBooksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'is_favorited' => $this->is_favorited,
            'category_id' => $this->category_id,
            'category_name' => $this->category->name,
            'author_name' => $this->user->first_name . ' ' . $this->user->last_name,
            'views_count' => $this->viewsCount(),
            'rating' => $this->getAverageRatingAttribute(),
            'comments_count' => $this->comments()->count() ?: null,

        ];
    }
}
