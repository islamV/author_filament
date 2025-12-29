<?php

namespace App\Http\Requests\v1\Author\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:categories,name',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ];
    }
}
