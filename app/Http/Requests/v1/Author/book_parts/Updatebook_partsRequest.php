<?php

namespace App\Http\Requests\v1\Author\book_parts;

use Illuminate\Foundation\Http\FormRequest;

class Updatebook_partsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => 'numeric|exists:books,id',
            'chapter' => 'string',
            'content' => 'string',

        ];
    }
}
