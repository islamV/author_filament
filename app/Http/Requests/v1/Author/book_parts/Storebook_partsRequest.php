<?php

namespace App\Http\Requests\v1\Author\book_parts;

use Illuminate\Foundation\Http\FormRequest;

class Storebook_partsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => 'required|numeric|exists:books,id',
            'chapter' => 'required|string',
            'content' => 'required|string',
        ];
    }
}
