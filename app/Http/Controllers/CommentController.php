<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;
use App\Services\FirebaseNotificationService;

class CommentController extends Controller
{
    public function getBookComments($bookId)
    {
        $book = Book::findOrFail($bookId);

        $comments = Comment::where('book_id', $bookId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'تم جلب التعليقات بنجاح',
            'data' => CommentResource::collection($comments)
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'comment' => 'required|string|min:3|max:1000',
        ]);

        $comment = Comment::create([
            'book_id' => $request->book_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'parent_id' => null,
            'is_author_reply' => false
        ]);

        $book = Book::find($request->book_id);

        if ($book && $book->user_id !== Auth::id()) {
            app(FirebaseNotificationService::class)->sendCustomNotification(
                'تعليق جديد على كتابك',
                'قام ' . Auth::user()->name . ' بالتعليق على كتابك "' . $book->title . '"',
                [$book->user_id],
                false,
                [
                    'type' => 'new_comment',
                    'book_id' => (string) $book->id,
                    'comment_id' => (string) $comment->id,
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة التعليق بنجاح',
            'data' => new CommentResource($comment->load('user'))
        ], 201);
    }


    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'comment' => 'required|string|min:3|max:1000',
        ]);

        $parentComment = Comment::with('book')->findOrFail($commentId);

       /* if ($parentComment->book->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالرد على هذا التعليق'
            ], 403);
        }*/

        $reply = Comment::create([
            'book_id' => $parentComment->book_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'parent_id' => $commentId,
            'is_author_reply' => $parentComment->book->user_id == Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الرد بنجاح',
            'data' => new CommentResource($reply->load('user'))
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|min:3|max:1000',
        ]);

        $comment = Comment::with('book')->findOrFail($id);

        if ($comment->user_id != Auth::id() && $comment->book->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتعديل هذا التعليق'
            ], 403);
        }

        $comment->update(['comment' => $request->comment]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث التعليق بنجاح',
            'data' => new CommentResource($comment->load('user'))
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::with('book')->findOrFail($id);
        $user = Auth::user();

        if ($comment->user_id != $user->id && $comment->book->user_id != $user->id && $user->role_id != 1) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بحذف هذا التعليق'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التعليق بنجاح'
        ]);
    }
}
