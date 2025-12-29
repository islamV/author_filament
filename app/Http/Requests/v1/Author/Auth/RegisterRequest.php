<?php

namespace App\Http\Requests\v1\Author\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Step 1 fields only
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'device_token' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|integer|in:3,4', // 3 = Author, 4 = Reader
        ];
    }
}
