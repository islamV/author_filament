<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
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
                $maxWords = \App\Models\Setting::first()?->book_content_word_limit ?? 1;

                $contentParts = $this->splitHtmlByWordsPreserveTags($bookPart->content, $maxWords);

                $parts = collect($contentParts)->map(function($content, $index){
                    return [
                        'part_number' => $index + 1,
                        'content' => $content,
                    ];
                });

                return [
                    'book_part_id' => $bookPart->id,
                    'chapter' => $bookPart->chapter,
                    'is_reviewed' => $bookPart->is_reviewed,
                    'created_at' => $bookPart->created_at->toDateTimeString(),
                    'content_parts' =>  $parts,
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
                    'status' => $book->status,
                ];
            }),
        ];
    }

    private function splitHtmlByWordsPreserveTags(string $html, int $maxWords = 1000): array
    {
        $pattern = '/(<[^>]+>|[^\s<>]+)/'; 
        preg_match_all($pattern, $html, $matches);

        $tokens = $matches[0];
        $chunks = [];
        $currentChunk = '';
        $currentWords = 0;
        $openTags = [];

        foreach ($tokens as $token) {
            if (preg_match('/<\s*\/(\w+)\s*>/', $token, $m)) {
                $tag = $m[1];
                $currentChunk .= $token;
                $key = array_search($tag, array_reverse($openTags, true));
                if ($key !== false) {
                    unset($openTags[$key]);
                    $openTags = array_values($openTags);
                }
            } elseif (preg_match('/<\s*(\w+)[^>]*>/', $token, $m)) {
                $tag = $m[1];
                $currentChunk .= $token;
                $openTags[] = $tag;
            } else {
                $currentChunk .= $token . ' ';
                $currentWords++;
            }

            if ($currentWords >= $maxWords) {
                for ($i = count($openTags) - 1; $i >= 0; $i--) {
                    $currentChunk .= "</{$openTags[$i]}>";
                }

                $chunks[] = $currentChunk;

                $currentChunk = '';
                foreach ($openTags as $tag) {
                    $currentChunk .= "<$tag>";
                }

                $currentWords = 0;
            }
        }

        if (trim(strip_tags($currentChunk))) {
            $chunks[] = $currentChunk;
        }

        return $chunks;
    }
}
