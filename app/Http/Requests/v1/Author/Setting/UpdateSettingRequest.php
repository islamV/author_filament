<?php

namespace App\Http\Requests\v1\Author\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can add authorization logic here if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Site information
            'site_name' => ['sometimes', 'string', 'max:255'],
            'site_description' => ['sometimes', 'string', 'max:1000'],
            'site_email' => ['sometimes', 'email', 'max:255'],
            'site_phone' => ['sometimes', 'string', 'max:50'],
            'site_address' => ['sometimes', 'string', 'max:500'],

            // Images
            'site_logo' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'site_favicon' => ['sometimes', 'image', 'mimes:ico,png', 'max:512'],
            'logo_image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'banner_image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],

            // Social media links
            'facebook' => ['sometimes', 'nullable', 'url', 'max:255'],
            'twitter' => ['sometimes', 'nullable', 'url', 'max:255'],
            'instagram' => ['sometimes', 'nullable', 'url', 'max:255'],
            'linkedin' => ['sometimes', 'nullable', 'url', 'max:255'],
            'youtube' => ['sometimes', 'nullable', 'url', 'max:255'],
            'tiktok' => ['sometimes', 'nullable', 'url', 'max:255'],
            'snapchat' => ['sometimes', 'nullable', 'url', 'max:255'],
            'pinterest' => ['sometimes', 'nullable', 'url', 'max:255'],
            'whatsapp' => ['sometimes', 'nullable', 'string', 'max:50'],
            'telegram' => ['sometimes', 'nullable', 'url', 'max:255'],

            // Policies and content
            'terms_conditions' => ['sometimes', 'nullable', 'string'],
            'privacy_policy' => ['sometimes', 'nullable', 'string'],
            'refund_policy' => ['sometimes', 'nullable', 'string'],
            'about_us' => ['sometimes', 'nullable', 'string'],
            'contact_info' => ['sometimes', 'nullable', 'string'],

            // SEO
            'meta_keywords' => ['sometimes', 'nullable', 'string', 'max:500'],
            'meta_description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'google_analytics' => ['sometimes', 'nullable', 'string', 'max:500'],
            'facebook_pixel' => ['sometimes', 'nullable', 'string', 'max:500'],

            // General settings
            'currency' => ['sometimes', 'string', 'max:10'],
            'timezone' => ['sometimes', 'string', 'max:50'],
            'language' => ['sometimes', 'string', 'max:10'],
            'maintenance_mode' => ['sometimes', 'boolean'],

            // Free period settings
            'free_period_enabled' => ['sometimes', 'boolean'],
            'free_period_days' => ['sometimes', 'integer', 'min:0', 'max:365'],
            'free_period_start' => ['sometimes', 'nullable', 'date'],
            'free_period_end' => ['sometimes', 'nullable', 'date', 'after:free_period_start'],
            'free_period_description' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'site_email.email' => __('validation.email', ['attribute' => __('settings.site_email')]),
            'site_logo.image' => __('validation.image', ['attribute' => __('settings.site_logo')]),
            'site_favicon.image' => __('validation.image', ['attribute' => __('settings.site_favicon')]),
            'free_period_end.after' => __('validation.after', ['attribute' => __('settings.free_period_end'), 'date' => __('settings.free_period_start')]),
        ];
    }
}

