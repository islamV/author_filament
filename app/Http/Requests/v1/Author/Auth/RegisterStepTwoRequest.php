<?php

namespace App\Http\Requests\v1\Author\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStepTwoRequest extends FormRequest
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
            'id' => 'required|exists:users,id',
            'country_id' => 'required|exists:registration_countries,id',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'invitation_code' => 'nullable|exists:users,invitation_code',
        ];
    }
}
