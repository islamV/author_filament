<?php

namespace App\Http\Resources\v1\Author\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorBookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author_id' => $this->user->id ?? null,
            'author_name' => $this->user->first_name . ' ' . $this->user->last_name ?? null,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'publish_status' => $this->publish_status,
            'status' => $this->status, 
            'category_id' => $this->category->id ?? null,
            'category_name' => $this->category->name ?? null,
            'views' => $this->viewsCount(),
            'average_rating' => $this->getAverageRatingAttribute(),
        ];
    }
}
