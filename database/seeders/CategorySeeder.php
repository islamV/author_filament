<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
             'id' => 1,
             'name' => 'التاريخ',
             'image' => 'التاريخ',
        ]);
        Category::create([
            'id' => 2,
            'name' => 'الخيال العلمي',
            'image' => 'الخيال العلمي',
        ]);
        Category::create([
            'id' => 3,
            'name' => 'العلوم',
            'image' => 'العلوم',
        ]);
        Category::create([
            'id' => 4,
            'name' => 'التكنولوجيا',
            'image' => 'التكنولوجيا',
        ]);
        Category::create([
            'id' => 5,
            'name' => 'التصميم',
            'image' => 'التصميم',
        ]);
        Category::create([
            'id' => 6,
            'name' => 'التعليم',
            'image' => 'التعليم',
        ]);
        Category::create([
            'id' => 7,
            'name' => 'التطوير الذاتي',
            'image' => 'التطوير الذاتي',
        ]);
        Category::create([
            'id' => 8,
            'name' => 'الاكثر شعبيه',
            'image' => 'الاكثر شعبيه',
        ]);
    }
}
