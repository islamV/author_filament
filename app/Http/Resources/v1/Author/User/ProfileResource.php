<?php

namespace App\Http\Resources\v1\Author\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'phone' => $this->phone,
            'plan' => $this->subscriptions()->active()->first()->type ?? 'default',
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
