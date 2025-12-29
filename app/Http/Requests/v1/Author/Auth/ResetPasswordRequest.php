<?php

namespace App\Http\Requests\v1\Author\Auth;;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed',
        ];
    }

    public function messages(){
        return [
            'email.required' => __('validation.required', ['attribute' => __('attributes.email')]),
            'email.email' => __('validation.email', ['attribute' => __('attributes.email')]),
            'email.exists' => __('validation.email', ['attribute' => __('attributes.email')]),
            'password.required' => __('validation.required', ['attribute' => __('attributes.password')]),
            'password.string' => __('validation.string', ['attribute' => __('attributes.password')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('attributes.password')]),
        ];

    }
}
