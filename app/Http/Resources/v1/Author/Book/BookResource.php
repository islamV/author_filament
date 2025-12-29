<?php

namespace App\Http\Resources\v1\Author\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author_id' => $this->user->id ?? null,
            'author_name' => ($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? ''),
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
            'views' => $this->viewsCount(),
            'average_rating' => $this->getAverageRatingAttribute(),
            'ratings' => $this->ratings->map(function ($rating) {
                return [
                    'score' => $rating->rating,
                    'comment' => $rating->comment,
                    'user_id' => $rating->user_id,
                    'user_name' => ($rating->user->first_name ?? '') . ' ' . ($rating->user->last_name ?? ''),
                    'user_image' => $rating->user && $rating->user->image ? asset('storage/' . $rating->user->image) : null,
                    'created_at' => $rating->created_at->toDateTimeString(),
                ];
            })->take(5),
            'similar_books' => $this->category->books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author_id' => $book->user->id ?? null,
                    'author_name' => ($book->user->first_name ?? '') . ' ' . ($book->user->last_name ?? ''),
                    'image' => $book->image ? asset('storage/' . $book->image) : null,
                    'views' => $book->viewsCount(),
                    'publish_status' => $book->publish_status,
                    'status' => $this->status,
                ];
            }),
        ];
    }
}
