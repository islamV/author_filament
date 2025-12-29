<?php

namespace App\Services\v1\Author\Book;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Traits\ImageTrait;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; 
use App\Repositories\v1\Interface\Dashboard\Ad\IAd;
use App\Repositories\v1\Interface\Author\Book\IBook;
use App\Repositories\v1\Interface\Author\Book\IBookPage;
use App\Repositories\v1\Interface\Author\book_parts\Ibook_parts;

class BookService
{
    use ImageTrait, ResponseTrait;

    protected IBook $book;
    protected Ibook_parts $ibook_parts;
    protected IBookPage $bookPage;
    protected IAd $ad;
    protected Category $category;

    public function __construct(IBook $book, Ibook_parts $ibook_parts, IBookPage $bookPage, IAd $ad)
    {
        $this->book = $book;
        $this->ibook_parts = $ibook_parts;
        $this->bookPage = $bookPage;
        $this->ad = $ad;
    }

    private function canPublishWithoutReview(User $user)
    {
        $setting = \App\Models\Setting::first();
        $roles = $setting?->roles->pluck('id')->toArray() ?? [];
        $users = $setting?->users->pluck('id')->toArray() ?? [];

        return in_array($user->id, $users) || in_array($user->role_id, $roles);
    }


    private function filterPublishedBooks($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->where('publish_status', 'published')
              ->orWhere(function ($sub) use ($now) {
                  $sub->where('publish_status', 'scheduled')
                      ->where('scheduled_until', '<=', $now);
              });
        })->with(['book_parts' => function ($q) {
            $q->where('is_reviewed', true);
        }]);
    }

    public function index($request, $limit)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category_id');
    
        $books = $this->book->get($query, $limit, $categoryId);

        $books->load(['book_parts' => function ($q) {
            $q->where('is_reviewed', true);
        }]);
        
        return  $books;
    }

    public function getAuthorBooks($request, $limit)
    {
        $published = $request->input('published');
        $books = $this->book->getAuthorBooks($published, $limit);
    
        $books->load(['book_parts']); 
        
        return $books;
    }

    public function getAuthorBook($book)
    {
        $userId = auth()->id();
        
        // ابحث عن الكتاب يدوياً مع user_id
        $book = Book::where('user_id', $userId)
                    ->with(['category', 'book_parts.bookPages'])
                    ->find($book->id);
        
        if (!$book) {
            throw new \Exception('الكتاب غير موجود أو ليس ملكك');
        }
        
        return $book;
    }
    public function getAuthorBookByAuthorId($book, $user)
    {
        $book->load(['book_parts']); 
        return $book;
    }
    public function store($request)
    {
        return DB::transaction(function () use ($request) {
            if (!Auth::check()) throw new \Exception(__('errors.user_not_authenticated'));
            $user = Auth::user();
            if (!$user) throw new \Exception(__('errors.user_not_found'));

            $image = $this->saveImage($request, 'image', 'Books/Images');
            $pdf = $this->saveImage($request, 'pdf_path', 'Books/Pdf');

            
            $canPublishDirectly = $this->canPublishWithoutReview($user);
            
            $publishStatus = $canPublishDirectly ? 'published' : 'draft';
            $bookStatus = $canPublishDirectly ? 'accepted' : 'pending'; 

            $book = $this->book->store([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $image,
                'pdf_path' => $pdf,
                'category_id' => $request->category_id,
                'user_id' => $user->id,
                'section_id' => $request->section_id,
                'publish_status' => $publishStatus,
                'status' => $bookStatus, 
            ]);

            if ($request->book_parts) {
                collect($request->book_parts)->each(function ($part) use ($book, $canPublishDirectly) {
                    $bookPart = $this->ibook_parts->store([
                        'book_id' => $book->id,
                        'chapter' => $part['chapter'],
                        'content'      => $part['content'],
                        'is_reviewed' => $canPublishDirectly,
                        'is_published' => $canPublishDirectly, 
                        'status' => $canPublishDirectly ? 'accepted' : 'pending', 
                    ]);
                    
                    $content = $part['content'];
                    $pages = str_split($content, 1000);
                    
                    foreach ($pages as $index => $pageContent) {
                        $bookPart->bookPages()->create([
                            'page_number' => $index + 1,
                            'content' => $pageContent,
                        ]);
                    }
                });
            }

            return $book->load(['book_parts' => function ($q) use ($canPublishDirectly) {
                // إذا كان المستخدم له صلاحية النشر، نعرض جميع الأجزاء
                // إذا لم يكن له صلاحية، نعرض فقط المراجعة
                $q->when(!$canPublishDirectly, function ($query) {
                    $query->where('is_reviewed', true);
                });
            }]);
        });
    }

    public function update($request, $book)
    {
        return DB::transaction(function () use ($request, $book) {
            $image = $this->updateImage($request, 'image', 'Books/Images', $book->image);
            $pdf = $this->updateImage($request, 'pdf_path', 'Books/Pdf', $book->pdf_path);

            $book->update([
                'title' => $request->title ?? $book->title,
                'description' => $request->description ?? $book->description,
                'image' => $image ?? $book->image,
                'pdf_path' => $pdf ?? $book->pdf_path,
                'category_id' => $request->category_id ?? $book->category_id,
            ]);

            if ($request->book_parts) {
                collect($request->book_parts)->each(function ($part) use ($book) {
                    $bookPart = $this->ibook_parts->find((int) $part['id']);
                    if ($bookPart) {
                        // 1. تحديث chapter
                        $bookPart->update([
                            'chapter' => $part['chapter'] ?? $bookPart->chapter,
                        ]);
                        
                        // 2. إذا كان هناك content جديد
                        if (isset($part['content']) && !empty($part['content'])) {
                            $content = $part['content'];
                            
                            // تقسيم المحتوى إلى أجزاء (1000 حرف لكل جزء)
                            $contentParts = str_split($content, 1000);
                            
                            // الجزء الأول يذهب إلى book_parts.content
                            $firstPart = $contentParts[0];
                            $bookPart->update(['content' => $firstPart]);
                            
                            // حذف الصفحات القديمة
                            $bookPart->bookPages()->delete();
                            
                            // الباقي يذهب إلى book_pages
                            if (count($contentParts) > 1) {
                                for ($i = 1; $i < count($contentParts); $i++) {
                                    $bookPart->bookPages()->create([
                                        'page_number' => $i, // الصفحة 1، 2، الخ
                                        'content' => $contentParts[$i],
                                    ]);
                                }
                            }
                        }
                    }
                });
            }

            return $book->load(['book_parts' => function ($q) {
                $q->where('is_reviewed', true);
            }]);
        });
    }

    public function delete($book)
    {
        $this->deleteImage($book->image);
        return $this->book->delete($book);
    }

    public function rate($book, $score, $request)
    {
        return $this->book->rate($book, $score, $request->comment);
    }

    public function getRate($book, $limit)
    {
        return $this->book->getRate($book, $limit);
    }

    public function getBooksByCategory($category, $limit)
    {
        $books = $this->book->getBookByCategory($category, $limit);
        return $this->filterPublishedBooks($books);
    }

    public function getHomePage($limit = 5)
    {
        $popularBooks = $this->book->getPopularBooks($limit);
        $popularBooks->load(['book_parts' => function ($q) {
            $q->where('is_reviewed', true);
        }]);
        
        $featuredBooks = $this->book->getFeaturedBooks($limit);
        $featuredBooks->load(['book_parts' => function ($q) {
            $q->where('is_reviewed', true);
        }]);
        
        $ads = $this->ad->get([], $limit);

        return [
            'ads' => $ads,
            'most_popular' => $popularBooks,
            'featured' => $featuredBooks,
        ];
    }

    public function getPopularBooks(Request $request, $limit = 10)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category_id');

        $books = $this->book->getPopularBooks($limit, $query, $categoryId);

        $books->load(['book_parts' => function ($q) {
            $q->where('is_reviewed', true);
        }]);

        return $books;
    }


    public function getFeaturedBooks($limit)
    {
        $books = $this->book->getFeaturedBooks($limit);
        
        $books->load(['book_parts' => function ($q) {
            $q->where('is_reviewed', true);
        }]);
        
        return $books; // ⬅️ بدون filterPublishedBooks
    }

    public function show($book)
    {
        return $this->book->show($book);
    }
}
