<?php

namespace App\Http\Resources\v1\Author\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'chat_room_id' => $this->chat_room_id,
            'sender_id' => $this->sender->id ?? null,
            'sender_name' => $this->sender->first_name . ' ' . $this->sender->last_name  ?? null,
            'sender_image' => $this->sender->image ?? null,
            'sender_phone' => $this->sender->phone ?? null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
