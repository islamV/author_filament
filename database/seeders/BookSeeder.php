<?php
namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'ارض زيكولا',
                'user_id' => 7,
                'description' => 'هذا الكتاب عن مغامرات فتحي اقرأ لتعرف التفاصيل',
                'image' => 'ارض زيكولا',
                'category_id' => 1,
                'is_published' => 1,
                'is_popular' => 1,
                'is_featured' => 1,
            ],
            [
                'title' => 'جلسات نفسيه',
                'user_id' => 8,
                'description' => 'يتحدث هذا الكتاب عن الهدوء والراحه النفسيه وكيفيه التعامل معاه',
                'image' => 'جلسات نفسيه',
                'category_id' => 2,
                'is_published' => 1,
                'is_popular' => 1,
                'is_featured' => 1,
            ],
            [
                'title' => 'من الصفر',
                'user_id' => 9,
                'description' => 'يتحدث هذا الكتاب عن كيفيه البدايه من الصفر',
                'image' => 'من الصفر',
                'category_id' => 3,
                'is_published' => 0,
                'is_popular' => 1,
                'is_featured' => 1,
            ],
            [
                'title' => 'التكنولوجيا الحديثه',
                'user_id' => 9,
                'description' => 'يتحدث هذا الكتاب عن التكنولوجيا الحديثه',
                'image' => 'التكنولوجيا الحديثه',
                'category_id' => 8,
                'is_published' => 1,
                'is_popular' => 1,
                'is_featured' => 1,
            ],
            [
                'title' => 'التصميم الجرافيكي',
                'user_id' => 9,
                'description' => 'يتحدث هذا الكتاب عن التصميم الجرافيكي',
                'image' => 'التصميم الجرافيكي',
                'category_id' => 8,
                'is_published' => 1,
                'is_popular' => 1,
                'is_featured' => 1,
            ],
            [
                'title' => 'التعليم الحديث',
                'user_id' => 9,
                'description' => 'يتحدث هذا الكتاب عن التعليم الحديث',
                'image' => 'التعليم الحديث',
                'category_id' => 8,
                'is_published' => 1,
                'is_popular' => 1,
                'is_featured' => 1,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
