<?php

namespace App\Http\Controllers\v1\Author\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\book_parts;
use App\Traits\FilterTrait;
use Illuminate\Http\Request;

class BookPartController extends Controller
{
    use FilterTrait;
    public function list(Book $book)
    {
        $book_parts = $book->book_parts()->with('bookPages')->paginate(10);
        return view('pages.book_parts.list', compact('book_parts', 'book'));
    }

    public function publish(book_parts $part)
    {

        $part->update(['is_published' => !$part->is_published]);


        $message = $part->is_published
            ? 'Book Published Successfully'
            : 'Book Unpublished Successfully';

        return redirect()->back()->with('status', $message);
    }

}
