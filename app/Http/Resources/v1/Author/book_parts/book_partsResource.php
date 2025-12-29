<?php

namespace App\Http\Resources\v1\Author\book_parts;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class book_partsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'chapter' => $this->chapter,
            'content' => $this->content,
            'views' => $this->viewsCount(),
        ];
    }
}
