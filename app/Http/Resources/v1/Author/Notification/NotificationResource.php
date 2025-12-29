<?php

namespace App\Http\Resources\v1\Author\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
//            'is_read' => $this->pivot->is_read,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
