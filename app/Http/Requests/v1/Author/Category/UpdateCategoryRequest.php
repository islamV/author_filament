<?php

namespace App\Http\Requests\v1\Author\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|unique:categories,name,' . $this->category->id,
            'image' => 'image|mimes:jpeg,png,jpg|max:5120',
        ];
    }
}
