<?php

namespace App\Http\Requests\v1\Author\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest extends FormRequest
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
            "role_id" => "nullable|numeric|exists:roles,id",
            "device_token" => "nullable|numeric",
        ];
    }
    public function messages()
    {
        return [
            'role_id.numeric' => __('messages.role_id.must_be_numeric'),
            'role_id.exists' => __('messages.role_id.not_found'),

            'provider.numeric' => __('messages.provider.must_be_numeric'),
            'provider.min' => __('messages.provider.out_of_range'),
            'provider.max' => __('messages.provider.out_of_range'),
        ];
    }
}
