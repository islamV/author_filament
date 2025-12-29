<?php

namespace App\Http\Resources\v1\Author\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->messages->map(function ($message) {
                return [
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->first_name . ' ' . $message->sender->last_name  ?? null,
                    'sender_image' => $message->sender->image ?? null,
                    'chat_room_id' => $message->chat_room_id,
                    'message' => $message->message,
                    'message_created_at' => $message->created_at->toDateTimeString(),
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
