<?php

namespace App\Http\Resources\v1\Author\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorProfileResource extends JsonResource
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
            'first_name' => $this->first_name ,
            'last_name' =>  $this->last_name,
            'email' => $this->email,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'phone' => $this->phone,
            'total_views' => $this->totalBooksViewsCount(),
            'rating' => $this->getAuthorAverageRatingAttribute(),
            'total_books' => $this->books->count(),
            'total_followers' => $this->followers()->count(),
            'profit' => $this->calculateEarnings(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
