<?php

namespace App\Http\Requests\v1\Author\Auth;;

use Illuminate\Foundation\Http\FormRequest;

class ValidateOTPRequest extends FormRequest
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
            'otp' => 'required',
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function messages()
    {
        return [
            'otp.required' => __('validation.required', ['attribute' => __('attributes.otp')]),
            'email.required' => __('validation.required', ['attribute' => __('attributes.email')]),
            'email.email'=> __('validation.email', ['attribute' => __('attributes.email')]),
            'email.exists' => __('validation.exists', ['attribute' => __('attributes.email')]),
        ];
    }
}
