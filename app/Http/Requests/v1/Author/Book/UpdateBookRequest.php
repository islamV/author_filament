<?php

namespace App\Http\Requests\v1\Author\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pdf_path' => 'nullable|file|mimes:pdf|max:2048',
            'category_id' => 'sometimes|exists:categories,id',
            'section_id' => 'nullable|exists:sections,id',
            'book_parts' => 'nullable|array',
            'book_parts.*.id' => 'nullable|integer|exists:book_parts,id', 
            'book_parts.*.chapter' => 'nullable|string|max:255',
            'book_parts.*.content' => 'nullable|string',
        ];
    }
}
