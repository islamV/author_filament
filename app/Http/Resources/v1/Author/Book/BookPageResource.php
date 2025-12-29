<?php

namespace App\Http\Resources\v1\Author\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookPageResource extends JsonResource
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
            'category_id' => $this->category->id ?? null,
            'category_name' => $this->category->name ?? null,
            'publish_status' => $this->publish_status,
            'status' => $this->status, 
            'book_parts' => $this->book_parts->map(function($bookPart){
                return [
                    'book_part_id' => $bookPart->id,
                    'chapter' => $bookPart->chapter,
                    'content' => $bookPart->content, 
                    'is_reviewed' => $bookPart->is_reviewed,
                    'created_at' => $bookPart->created_at->toDateTimeString(),
                    'book_pages' => $bookPart->bookPages->map(function($bookPage){
                        return [
                            'book_page_id' => $bookPage->id,
                            'page_number' => $bookPage->page_number,
                            'content' => $bookPage->content, 
                            'created_at' => $bookPage->created_at->toDateTimeString(),
                        ];
                    })
                ];
            }),
        ];
    }
}
