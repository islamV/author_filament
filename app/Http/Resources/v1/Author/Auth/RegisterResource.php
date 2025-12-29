<?php

namespace App\Http\Resources\v1\Author\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone ?? null,
            'role_id' => $this->role_id,
            'role_name'=>$this->role->name,
            'status' => $this->status,
            'device_token' => $this->token ?? null, // Token only available after Step 2 or login
            'work_link'    => $this->work_link ?? null,
            'description'  => $this->description ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
