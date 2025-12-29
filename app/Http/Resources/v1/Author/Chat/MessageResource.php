<?php

namespace App\Http\Resources\v1\Author\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender->id ?? null,
            'sender_name' => $this->sender->first_name . ' ' . $this->sender->last_name  ?? null,
            'sender_image' => $this->sender->image ?? null,
            'sender_phone' => $this->sender->phone ?? null,
            'receiver_id' => $this->receiver->id ?? null,
            'receiver_name' => $this->receiver->first_name . ' ' . $this->receiver->last_name  ?? null,
            'receiver_image' => $this->receiver->image ?? null,
            'receiver_phone' => $this->receiver->phone ?? null,
            'message' => $this->messages->map(function ($message) {
                return [
                    'sender_id' => $message->sender_id,
                    'chat_room_id' => $message->chat_room_id,
                    'message' => $message->message,
                    'message_created_at' => $message->created_at->toDateTimeString(),
                ];
            }),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
