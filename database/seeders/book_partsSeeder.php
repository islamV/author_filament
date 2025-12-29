<?php

namespace Database\Seeders;

use App\Models\book_parts;
use Illuminate\Database\Seeder;

class book_partsSeeder extends Seeder
{
    public function run(): void
    {
        book_parts::create([
            'book_id' => 1,
            'chapter' => 'الفصل الاول ',
        ]);

        book_parts::create([
            'book_id' => 1,
            'chapter' => 'الفصل الثاني ',
        ]);

        book_parts::create([
            'book_id' => 2,
            'chapter' => 'الفصل الاول ',
        ]);

        book_parts::create([
            'book_id' => 2,
            'chapter' => 'الفصل الثاني ',
        ]);

        book_parts::create([
            'book_id' => 3,
            'chapter' => 'الفصل الاول ',
        ]);

        book_parts::create([
            'book_id' => 3,
            'chapter' => 'الفصل الثاني ',
        ]);

    }
}
