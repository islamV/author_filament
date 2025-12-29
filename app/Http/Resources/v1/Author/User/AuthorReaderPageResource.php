<?php

namespace App\Http\Resources\v1\Author\User;

use App\Http\Resources\v1\Author\Book\HomePageBooksResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorReaderPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user'=> new AuthorProfileResource($this['user']),
            'latest_books' => HomePageBooksResource::collection($this['latest_books']->items()),
        ];
    }
}
