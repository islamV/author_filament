<?php

namespace App\Http\Resources\v1\Author\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? NULL, // If messages are null, return null
            'last_message' => $this->messages ?? NULL, // If messages are null, return null
            'chat_type_id' => $this->chat_type_id ?? null,
            'sender_id' => $this->sender->id ?? null,
            'sender_name' => $this->sender ? $this->sender->first_name . ' ' . $this->sender->last_name : null,
            'sender_image' => $this->sender->image ?? null,
            'receiver_id' => $this->receiver->id ?? null,
            'receiver_name' => $this->receiver ? $this->receiver->first_name . ' ' . $this->receiver->last_name : null,
            'receiver_image' => $this->receiver->image ?? null,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
        ];
    }

}
