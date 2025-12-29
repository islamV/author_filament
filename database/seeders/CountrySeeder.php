<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            [
                'name' => 'مصر',
                'code' => 'EG',
                'view_count' => 1000,
                'price' => 200,
            ],
            [
                'name' => 'السعودية',
                'code' => 'SA',
                'view_count' => 1000,
                'price' => 180,
            ],
            [
                'name' => 'الإمارات',
                'code' => 'AE',
                'view_count' => 1000,
                'price' => 190,
            ],
            [
                'name' => 'الكويت',
                'code' => 'KW',
                'view_count' => 1000,
                'price' => 195,
            ],
            [
                'name' => 'المغرب',
                'code' => 'MA',
                'view_count' => 1000,
                'price' => 150,
            ],
            [
                'name' => 'أستراليا',
                'code' => 'AU',
                'view_count' => 1000,
                'price' => 36.50,
            ],
            [
                'name' => 'أمريكا',
                'code' => 'US',
                'view_count' => 1000,
                'price' => 36.00,
            ],
            [
                'name' => 'بريطانيا',
                'code' => 'GB',
                'view_count' => 1000,
                'price' => 36.00,
            ],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}