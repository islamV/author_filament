<?php

namespace App\Http\Controllers\v1\Author\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Author\Book\StoreBookRequest;
use App\Http\Requests\v1\Author\Book\StoreOrUpdateBookRateRequest;
use App\Http\Requests\v1\Author\Book\UpdateBookRequest;
use App\Http\Resources\PageResource;
use App\Http\Resources\v1\Author\Book\AuthorBookResource;
use App\Http\Resources\v1\Author\Book\BookPageResource;
use App\Http\Resources\v1\Author\Book\BookResource;
use App\Http\Resources\v1\Author\Book\HomePageBooksResource;
use App\Http\Resources\v1\Author\Book\HomePageResource;
use App\Http\Resources\v1\Author\Category\CategoryResource;
use App\Models\{Book, Category, User};
use App\Services\v1\Author\Book\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function category(Request $request)
    {
        $categories = Category::query()
            ->when($request->has('query'), fn($q) => $q->where('name', 'LIKE', '%' . $request->input('query') . '%'))
            ->orderBy('name')
            ->paginate($request->get('per_page', 10));

        return $this->returnData(__('messages.category.list'), 200, CategoryResource::collection($categories));
    }

    public function index(Request $request)
    {
        return $this->returnData(__('messages.book.list'), 200,
            BookResource::collection($this->bookService->index($request, $request->get('per_page', 10))));
    }

    public function getAuthorBooks(Request $request)
    {

        return $this->returnData(__('messages.book.list'), 200,
            AuthorBookResource::collection($this->bookService->getAuthorBooks($request, $request->get('per_page', 10))));
    }

    public function getAuthorBook(Book $book)
    {
        $book->addView();
        return $this->returnData(__('messages.book.list'), 200,
            new BookPageResource($this->bookService->getAuthorBook($book)));
    }

    public function getAuthorBookByAuthorId(Book $book, User $user)
    {
        $book->addView();
        return $this->returnData(__('messages.book.list'), 200,
            new BookPageResource($this->bookService->getAuthorBookByAuthorId($book, $user)));
    }

    public function store(StoreBookRequest $request)
    {
        $this->bookService->store($request);
        return $this->success(__('messages.book.added'), 200);
    }

    public function show(Book $book)
    {
        $book->addView();

        return $this->returnData(__('messages.book.details'), 200,
            new PageResource($this->bookService->show($book)));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $this->bookService->update($request, $book);
        return $this->success(__('messages.book.updated'), 200);
    }

    public function destroy(Book $book)
    {
        $this->bookService->delete($book);
        return $this->success(__('messages.book.deleted'), 200);
    }

    public function rate(Book $book, StoreOrUpdateBookRateRequest $request)
    {
        $this->bookService->rate($book, $request->score, $request);
        return $this->success(__('messages.book.rated'), 200);
    }

    public function getRate(Book $book, Request $request)
    {
        return $this->returnData(__('messages.book.rated'), 200,
            $this->bookService->getRate($book, $request->get('per_page', 10)));
    }

    public function getBooksByCategory(Category $category, Request $request)
    {
        return $this->returnData(__('messages.book.list'), 200,
            HomePageBooksResource::collection($this->bookService->getBooksByCategory($category, $request->get('per_page', 10))));
    }

    public function getHomePage(Request $request)
    {
        return $this->returnData(__('messages.book.list'), 200,
            new HomePageResource($this->bookService->getHomePage($request->get('per_page', 10))));
    }

    public function getPopularBooks(Request $request)
    {
        return $this->returnData(
            __('messages.book.list'), 
            200,
            HomePageBooksResource::collection(
                $this->bookService->getPopularBooks($request, 10) // 10 is the fixed limit
            )
        );
    }

    public function getFeaturedBooks(Request $request)
    {
        return $this->returnData(__('messages.book.list'), 200,
            HomePageBooksResource::collection($this->bookService->getFeaturedBooks($request->get('per_page', 10))));
    }

    public static function download(Book $book)
    {
        $user = auth()->user();

        if (!$book->pdf_path) {
            return response()->json(['error' => 'PDF not found for this book.'], 404);
        }

        if ($user->is_trial_user && $user->trial_expires_at && now()->lessThanOrEqualTo($user->trial_expires_at)) {
            return response()->json([
                'download_link' => asset('storage/' . $book->pdf_path),
                'downloads' => 'Unlimited (trial)',
            ]);
        }

        $subscription = $user->subscriptions()->active()->first();

        if (!$subscription) {
            return response()->json(['message' => 'No active subscription found'], 403);
        }

        $plan = $subscription->plan;

        if (!$plan) {
            return response()->json(['message' => 'Plan not found for subscription'], 403);
        }

        if ($subscription->downloads >= $plan->max_downloads) {
            return response()->json(['message' => 'Download limit reached'], 403);
        }

        $subscription->increment('downloads');

        return response()->json([
            'download_link' => asset('storage/' . $book->pdf_path),
            'downloads' => $subscription->downloads,
        ]);
    }



    public function deleteDownload()
    {
        $user = auth()->user();

        if ($user->downloads > 0) {
            $user->decrement('downloads');
        }

        return response()->json([
            'message' => 'Download count updated successfully.',
            'downloads' => $user->downloads,
        ]);
    }

    public function bookRefuseReason(Request $request, $book)
    {
        $user = $request->user();

        $book = Book::where('id', $book)
            ->where('user_id', $user->id)
            ->first();

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'الكتاب غير موجود أو ليس ملكًا للمستخدم',
            ], 404);
        }

        if ($book->status !== 'refused') {
            return response()->json([
                'status' => 'error',
                'message' => 'هذا الكتاب لم يتم رفضه',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'refusal_reason' => $book->refusal_reason,
            ],
        ]);
    }


    public function createInvitationCode()
    {
        $user = auth()->user();

        if (!in_array((int) $user->role_id, [2, 3])) {
            return response()->json([
                'success' => false,
                'message' => 'Only authors can create an invitation code.',
            ], 403);
        }

        if ($user->invitation_code) {
            return response()->json([
                'success' => false,
                'message' => 'Invitation code already exists.',
            ], 422);
        }

        $user->createInvitationCode();

        return response()->json([
            'success' => true,
            'message' => 'Invitation code created successfully.',
            'data' => [
                'invitation_code' => $user->invitation_code,
            ],
        ], 200);
    }


}
