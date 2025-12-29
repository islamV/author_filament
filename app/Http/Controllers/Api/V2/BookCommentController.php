<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBookCommentRequest;

class BookCommentController extends Controller
{
    public function store(StoreBookCommentRequest $request)
    {
        $comment = Comment::create([
            'book_id' => $request->book_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => [
                'id' => $comment->id,
                'book_id' => $comment->book_id,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                ],
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->toDateTimeString(),
            ]
        ], 201);

    }
}
