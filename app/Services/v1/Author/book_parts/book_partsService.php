<?php

namespace App\Services\v1\Author\book_parts;

use App\Repositories\v1\Interface\Author\book_parts\Ibook_parts;

class book_partsService
{
    protected Ibook_parts $book_parts;

    public function __construct(Ibook_parts $book_parts)
    {
        $this->book_parts = $book_parts;
    }

    public function index($request,$limit)
    {
        $query = $request->input('query');
        $book_id = $request->book_id;
        return $this->book_parts->get($query,$book_id,$limit);
    }

    public function store($request)
    {
        $book_parts = [
            'book_id' => $request->book_id,
            'chapter' => $request->chapter,
        ];

        $bookPart = $this->book_parts->store($book_parts);

        $content=$request['content'];

        if (!empty($content)) {
            $pages = str_split($content, 1000);

            foreach ($pages as $index => $pageContent) {
                $bookPart->bookPages()->create([
                    'page_number' => $index + 1,
                    'content'     => $pageContent,
                    'comment'     => 'Page ' . ($index + 1),
                ]);
            }
        }

        return $bookPart;
    }

    public function show($book_parts)
    {
        return $this->book_parts->show($book_parts->id);
    }

    public function update($request, $book_parts)
    {
        $book_parts->book_id = $request->book_id ?? $book_parts->book_id;
        $book_parts->chapter = $request->chapter ?? $book_parts->chapter;
        $book_parts->content = $request->content ?? $book_parts->content;

        return $this->book_parts->update($book_parts);
    }

    public function delete($book_parts)
    {
        return $this->book_parts->delete($book_parts);
    }
}
