<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookPage;
use App\Models\book_parts;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\v1\Author\Book\BookResource;
use App\Http\Requests\v1\Author\Book\UpdateBookRequest;
use App\Http\Resources\v1\Author\Book\BookPageResource;

class BookV2Controller extends Controller
{
    public function getAuthorBook($bookId)
    {
        $userId = Auth::id();
        
        $book = Book::where('user_id', $userId)->find($bookId);
        
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }
        
        $book->load(['category', 'book_parts', 'book_parts.bookPages']);
        
        return $this->returnData(__('messages.book.list'), 200,
            new BookPageResource($book));
    }


    public function update(UpdateBookRequest $request, $bookId)
    {
        $userId = Auth::id();
        
        $book = Book::where('user_id', $userId)->find($bookId);
        
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }
        
        $image = $book->image;
        if ($request->hasFile('image')) {
            if ($image && Storage::exists($image)) {
                Storage::delete($image);
            }
            $image = $request->file('image')->store('Books/Images', 'public');
        }
        
        $pdf = $book->pdf_path;
        if ($request->hasFile('pdf_path')) {
            if ($pdf && Storage::exists($pdf)) {
                Storage::delete($pdf);
            }
            $pdf = $request->file('pdf_path')->store('Books/Pdf', 'public');
        }
        
        $book->update([
            'title' => $request->input('title', $book->title),
            'description' => $request->input('description', $book->description),
            'image' => $image,
            'pdf_path' => $pdf,
            'category_id' => $request->input('category_id', $book->category_id),
            'section_id' => $request->input('section_id', $book->section_id),
        ]);
        
        if ($request->has('book_parts')) {
            foreach ($request->input('book_parts') as $part) {
                if (isset($part['id'])) {
                    $bookPart = book_parts::where('book_id', $book->id)
                                        ->find($part['id']);
                    
                    if ($bookPart) {
                        $bookPart->update([
                            'chapter' => $part['chapter'] ?? $bookPart->chapter,
                        ]);
                        
                        if (isset($part['content'])) {
                            $content = $part['content'];
                            $contentParts = str_split($content, 1000);
                            
                            $bookPart->update(['content' => $contentParts[0]]);
                            $bookPart->bookPages()->delete();
                            
                            if (count($contentParts) > 1) {
                                for ($i = 1; $i < count($contentParts); $i++) {
                                    $bookPart->bookPages()->create([
                                        'page_number' => $i,
                                        'content' => $contentParts[$i],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        
       return $this->returnData(__('messages.book.updated'), 200,
        new BookPageResource($book->load(['book_parts', 'book_parts.bookPages'])));
    }
}
