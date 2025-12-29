<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'site_name' => 'مكتبة المؤلفين',
            'site_logo' => 'settings/logo.png', 
            'logo_image' => 'settings/logo.png',
            'banner_image' => 'settings/banner.png',
            'site_favicon' => 'settings/favicon.ico',
            'site_description' => 'أفضل منصة للكتب والمؤلفين والكتابة.',
            'site_email' => 'info@authorslibrary.com',
            'site_phone' => '+201234567890',
            'site_address' => 'الشارع الرئيسي، المدينة، مصر',

            'facebook' => 'https://facebook.com/authorslibrary',
            'twitter' => 'https://twitter.com/authorslibrary',
            'instagram' => 'https://instagram.com/authorslibrary',
            'linkedin' => 'https://linkedin.com/company/authorslibrary',
            'youtube' => 'https://youtube.com/authorslibrary',
            'tiktok' => 'https://tiktok.com/@authorslibrary',
            'snapchat' => 'https://snapchat.com/add/authorslibrary',
            'pinterest' => 'https://pinterest.com/authorslibrary',
            'whatsapp' => '+201234567890',
            'telegram' => 'https://t.me/authorslibrary',

            'terms_conditions' => 'هنا نص الشروط والأحكام الخاصة بالموقع.',
            'privacy_policy' => 'هنا نص سياسة الخصوصية.',
            'refund_policy' => 'هنا نص سياسة الاسترجاع.',
            'about_us' => 'مكتبة المؤلفين هي منصة تهدف لدعم الكتاب والمؤلفين ونشر أعمالهم.',
            'contact_info' => 'يمكنك التواصل معنا عبر البريد الإلكتروني أو الهاتف.',

            'meta_keywords' => 'كتب, مؤلفين, كتابة, مكتبة, قراءة',
            'meta_description' => 'أفضل منصة للكتب والمؤلفين والكتابة والنشر.',
            'google_analytics' => '',
            'facebook_pixel' => '',

            'currency' => 'EGP',
            'timezone' => 'Africa/Cairo',
            'language' => 'ar',
            'maintenance_mode' => false,

            'free_period_enabled' => true,
            'free_period_days' => 7,
            'free_period_start' => now(),
            'free_period_end' => now()->addDays(7),
            'free_period_description' => 'احصل على فترة مجانية لتجربة الموقع قبل الاشتراك.',
        ]);
    }
}
