<?php

namespace App\Http\Resources\v1\Author\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->first_name . ' ' . $this->last_name,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'total_followers' => $this->followers()->count(),
        ];
    }
}
