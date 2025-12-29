<?php

namespace App\Http\Requests\v1\Author\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateBookRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'score' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ];
    }

}
