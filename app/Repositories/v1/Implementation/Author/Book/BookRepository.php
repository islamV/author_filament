<?php

namespace App\Repositories\v1\Implementation\Author\Book;

use App\Models\Book;
use App\Models\Rating;
use App\Models\Setting;
use App\Models\Favourite;
use Illuminate\Support\Facades\Auth;
use App\Repositories\v1\Interface\Author\Book\IBook;

class BookRepository implements IBook
{
    protected function filterPublishedBooks($query)
    {
        $setting = Setting::first();
        $roles = $setting?->publish_roles ?? [];
        $users = $setting?->publish_users ?? [];
        $now = now();

        return $query->where(function ($q) use ($roles, $users, $now) {
            $q->where('publish_status', 'published')
              ->orWhere(function ($q2) use ($now) {
                  $q2->where('publish_status', 'scheduled')
                     ->where('scheduled_until', '<=', $now);
              })
              ->orWhereIn('user_id', $users)
              ->orWhereHas('user.roles', function ($q3) use ($roles) {
                  $q3->whereIn('name', $roles);
              });
        });
    }

    protected function filterBookParts($book)
    {
        return $book->book_parts()->where('status', 'accepted')->get();
    }

    public function get($request, $limit = 10 , $categoryId = null)
    {
        $query = Book::with(['user', 'category', 'book_parts' => function ($q) {
            $q->where('is_reviewed', true);
        }])
        ->where('status', 'accepted');

        if ($request && !empty($request)) {
            $query->where('title', 'LIKE', "%{$request}%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $query = $this->filterPublishedBooks($query);
        return $query->paginate($limit);
    }

    public function getAuthorBooks($published, $limit = 10)
    {
        $query = Book::where('user_id', Auth::id());

        if (isset($published)) {
            $query->where('publish_status', $published);
        }

        return $query->paginate($limit);
    }

    public function getAuthorBook($book, $userId)
    {
        $book->addView();
        return Book::where('user_id', $userId)
            ->with(['category', 'book_parts.bookPages'])
            ->findOrFail($book->id);
    }

    public function getAuthorBookForUser($book, $userId)
    {
        $book->addView();
        return Book::where('user_id', $userId)
            ->with(['category', 'book_parts', 'book_parts.bookPages']) 
            ->findOrFail($book->id);
    }

    public function show($model)
    {
        $query = Book::where('status', 'accepted') 
                    ->with(['category.books' => function ($query) use ($model) {
                        $query->where('id', '!=', $model->id)
                            ->inRandomOrder()
                            ->take(2);
                    }, 'book_parts' => function ($query) {
                        $query->where('is_reviewed', true);
                    }]);
        
        return $this->filterPublishedBooks($query)
                ->findOrFail($model->id);
    }

    public function store($model)
    {
        return Book::create($model);
    }

    public function update($model)
    {
        return $model->save();
    }

    public function delete($model)
    {
        return $model->delete();
    }

    public function rate($model, $score, $comment = null)
    {
        return $model->rate($score, $comment);
    }

    public function getRate($model, $limit)
    {
        return Rating::select("id", "rating", "comment", "updated_at", "user_id")
            ->with(['user' => function ($query) {
                $query->selectRaw('id, CONCAT(first_name, " ", last_name) as username');
            }])
            ->where("book_id", $model->id)
            ->orderBy("created_at", "desc")
            ->paginate($limit);
    }

    public function getBookByCategory($model, $limit = 10)
    {
        $userId = auth()->id();

        return $this->filterPublishedBooks(
            Book::where('category_id', $model->id)
                ->addSelect([
                    'is_favorited' => Favourite::selectRaw('COUNT(*)')
                        ->whereColumn('favoritable_id', 'books.id')
                        ->where('favoritable_type', Book::class)
                        ->where('user_id', $userId)
                ])
        )->paginate($limit);
    }

    public function getPopularBooks($limit = 10, $query = null, $categoryId = null)
    {
        $userId = auth()->id();

        $booksQuery = Book::where('is_popular', 1)
            ->where('status', 'accepted')
            ->with('user:id,first_name,last_name')
            ->addSelect([
                'is_favorited' => Favourite::selectRaw('COUNT(*)')
                    ->whereColumn('favoritable_id', 'books.id')
                    ->where('favoritable_type', Book::class)
                    ->where('user_id', $userId)
            ]);

        if ($query) {
            $booksQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($categoryId) {
            $booksQuery->where('category_id', $categoryId);
        }

        return $this->filterPublishedBooks($booksQuery)
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
    }


    public function getFeaturedBooks($limit = 10)
    {
        $userId = auth()->id();

        return $this->filterPublishedBooks(
            Book::where('is_featured', 1)
                ->where('status', 'accepted')
                ->with('user:id,first_name,last_name')
                ->addSelect([
                    'is_favorited' => Favourite::selectRaw('COUNT(*)')
                        ->whereColumn('favoritable_id', 'books.id')
                        ->where('favoritable_type', Book::class)
                        ->where('user_id', $userId)
                ])
        )->paginate($limit);
    }
}
