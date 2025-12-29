<?php

namespace App\Http\Controllers\v1\Author\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Services\v1\Dashboard\Notification\NotificationService;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class BookController extends Controller
{
    use ImageTrait;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function list()
    {
        $books = Book::orderBy('is_published','asc')->orderBy('created_at','desc')->paginate(10);
        return view('pages.books.book-management' , compact('books'));
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('pages.books.edit-book' , compact('book' , 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $imagePath  = $this->updateImage($request,'image', 'books' , $book->image);
        $book->update([
            'title' => $request->title ?? $book->category_id,
            'description' => $request->description ?? $book->category_id,
            'category_id' => $request->category ?? $book->category_id,
            'image' => $imagePath ?? $book->image,
        ]);
        return redirect()->route('books.list')->with('status' , 'Book Updated Successfully');
    }

    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $this->deleteImage($book->image);
        $book->delete();
        return redirect()->route('books.list')->with('status' , 'Book Deleted Successfully');
    }

    public function publish($id)
    {
        $book = Book::findOrFail($id);
        if ($book->is_published == 1)
        {
            $book->update(['is_published' => 0,]);
            return redirect()->route('books.list')->with('status' , 'Book Unpublished Successfully');
        }
        else
        {
            $this->notificationService->sendNotificationToFollowers($book->user, $book);
            $book->update(['is_published' => 1,]);
            return redirect()->route('books.list')->with('status' , 'Book Published Successfully');
        }
    }
}
