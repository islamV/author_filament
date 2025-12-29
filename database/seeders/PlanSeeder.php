<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {

       Plan::create([
            'name' => 'الفضية',
            'price' => "4.49$",
            'description' => 'اكتشف باقتنا القياسية',
            'features' => [
                'الوصول الي 20 كتاب محمل.',
                'بلا إعلانات.',
                'مدير حساب مخصص.',
            ],
        ]);

        Plan::create([
            'name' => 'الذهبيه',
            'price' => "6.99$",
            'description' => 'اكتشف باقتنا الذهبية',
            'features' => [
                'الوصول الي 100 كتاب محمل.',
                'بلا إعلانات.',
                'الدعم الفني على مدار 24/7.',
            ],
        ]);

    }
}
