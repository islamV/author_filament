<?php

namespace App\Http\Resources\v1\Author\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterStepTwoResource extends JsonResource
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
            'phone' => $this->phone,
            'country_id' => $this->country_id,
            'country_name' => $this->registrationCountry->name,
            'role_id' => $this->role_id,
            'role_name'=>$this->role->name,
            'status' => $this->status,
            'token' => $this->token ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
