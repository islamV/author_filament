<?php

namespace App\Http\Requests\v1\Author\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'device_token' => 'required|string',
            'phone' => 'required|string|unique:users,phone',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            "description" => "required|string",
            'work_link' => 'nullable|string|url|max:255',

        ];
    }
}
