<?php

namespace App\Http\Resources\v1\Author\Book;

use App\Http\Resources\v1\Author\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookCategoryResource extends JsonResource
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
            'image' => $this->image,
            'views_count' => $this->viewsCount(),
            'is_favorited' => $this->is_favorited,
            'category_id' => $this->category_id,
            'category' => $this->category->name,
        ];
    }
}
