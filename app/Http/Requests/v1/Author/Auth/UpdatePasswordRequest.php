<?php

namespace App\Http\Requests\v1\Author\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => 'required|string',
            "password" => "required|string|min:6|confirmed",
        ];
    }
}
