<?php

namespace App\Http\Controllers\v1\Author\book_parts;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Author\book_parts\Storebook_partsRequest;
use App\Http\Requests\v1\Author\book_parts\Updatebook_partsRequest;
use App\Http\Resources\v1\Author\book_parts\book_partsResource;
use App\Models\{Book, book_parts};
use App\Services\v1\Author\book_parts\book_partsService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class book_partsController extends Controller
{
    private book_partsService $book_partsService;
    public function __construct(book_partsService $book_partsService)
    {
        $this->book_partsService = $book_partsService;
    }

    public function index(Request $request)
    {
        return $this->returnData(__('messages.book_parts.list'),200,
            book_partsResource::collection($this->book_partsService->index($request,$request->get('per_page',10))));
    }

    public function store(Storebook_partsRequest $request)
    {
        $this->book_partsService->store($request);
        return $this->success(__('messages.book_parts.added'),200);
    }

    public function show(book_parts $book_part)
    {
        return $this->returnData(__('messages.book_parts.details'),200,
            new book_partsResource($this->book_partsService->show($book_part)));
    }

    public function update(Updatebook_partsRequest $request, book_parts $book_parts)
    {
        $this->book_partsService->update($request,$book_parts);
        return $this->success(__('messages.book_parts.updated'),200);
    }
    
    public function destroy(book_parts $book_parts)
    {
        $this->book_partsService->delete($book_parts);
        return $this->success(__('messages.book_parts.deleted'),200);
    }

}
