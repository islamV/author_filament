<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        Ad::create([
             'id' => 1,
             'title' => 'اشترك خمس شهور بنصف السعر',
        ]);
        Ad::create([
            'id' => 2,
            'title' => 'شاهد الان الكاتب حامد',
        ]);
    }
}
