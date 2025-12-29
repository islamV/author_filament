<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['name' => 'قصص'],
            ['name' => 'روايات'],
            ['name' => 'كتاب'],
            ['name' => 'مقالات'],
            ['name' => 'شعر'],
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
