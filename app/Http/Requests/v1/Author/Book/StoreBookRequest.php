<?php

namespace App\Http\Requests\v1\Author\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pdf_path' => 'nullable|file|mimes:pdf|max:2048',
            'category_id' => 'required|exists:categories,id',
            'section_id' => 'required|exists:sections,id',
            //book parts(chapter,pages(page_number,content))
            'book_parts' => 'nullable|array',
            'book_parts.*.chapter' => 'required|string|max:255',
            'book_parts.*.content' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => __("messages.title.required"),
            'title.string' => __("messages.title.string"),
            'title.max' => __("messages.title.max"),

            'description.required' => __("messages.description.required"),
            'description.string' => __("messages.description.string"),

            'image.required' => __("messages.image.required"),
            'image.image' => __("messages.image.image"),
            'image.mimes' => __("messages.image.mimes"),
            'image.max' => __("messages.image.max"),

            'pdf.file' => __("messages.pdf.file"),
            'pdf.mimes' => __("messages.pdf.mimes"),
            'pdf.max' => __("messages.pdf.max"),

            'category_id.required' => __("messages.category_id.required"),
            'category_id.exists' => __("messages.category_id.exists"),

            'book_parts.array' => __("messages.book_parts.array"),
            'book_parts.*.chapter.required' => __("messages.book_parts.chapter.required"),
            'book_parts.*.chapter.string' => __("messages.book_parts.chapter.string"),
            'book_parts.*.chapter.max' => __("messages.book_parts.chapter.max"),

            'book_page.array' => __("messages.book_page.array"),
            'book_page.*.page_number.required' => __("messages.book_page.page_number.required"),
            'book_page.*.page_number.integer' => __("messages.book_page.page_number.integer"),
            'book_page.*.page_number.min' => __("messages.book_page.page_number.min"),
            'book_page.*.content.required' => __("messages.book_page.content.required"),
            'book_page.*.content.string' => __("messages.book_page.content.string"),
        ];
    }

}
