<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate([], [
            'site_name' => 'Quick',
            'site_logo' => null,
            'site_favicon' => null,
            'banner_image' => null,
            'logo_image' => null,
            'site_description' => 'تطبيق Quick للقراء والمؤلفين',
            'site_email' => 'info@quickapp.com',
            'site_phone' => '+201000000000',
            'site_address' => 'مصر',

            'facebook' => null,
            'twitter' => null,
            'instagram' => null,
            'linkedin' => null,
            'youtube' => null,
            'tiktok' => null,
            'snapchat' => null,
            'pinterest' => null,
            'whatsapp' => null,
            'telegram' => null,

            'terms_conditions' => 'هنا يتم إضافة الشروط والأحكام',
            'privacy_policy' => 'هنا يتم إضافة سياسة الخصوصية',
            'refund_policy' => 'هنا يتم إضافة سياسة الاسترجاع',
            'about_us' => 'Quick هو تطبيق للقراء والمؤلفين لمشاركة المحتوى بسهولة.',
            'contact_info' => 'info@quickapp.com',

            'meta_keywords' => 'Quick, قراء, مؤلفين, كتب, محتوى',
            'meta_description' => 'Quick هو تطبيق للقراء والمؤلفين لمشاركة المحتوى بسهولة.',
        ]);
    }
}
