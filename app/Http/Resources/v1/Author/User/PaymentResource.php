<?php

namespace App\Http\Resources\v1\Author\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'requested_amount' => $this->requested_amount,
        ];
    }
}
