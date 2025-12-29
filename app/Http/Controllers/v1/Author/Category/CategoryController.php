<?php

namespace App\Http\Controllers\v1\Author\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Author\Category\StoreCategoryRequest;
use App\Http\Requests\v1\Author\Category\UpdateCategoryRequest;
use App\Http\Resources\v1\Author\Category\CategoryResource;
use App\Models\{Category};
use App\Services\v1\Author\Category\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    // public function index(Request $request)
    // {
    //     return $this->returnData(__('messages.category.list'),200,
    //         CategoryResource::collection($this->categoryService->index($request,$request->get('per_page',10))));
    // }
    public function store(StoreCategoryRequest $request)
    {
        $this->categoryService->store($request);
        return $this->success(__('messages.category.added'),200);
    }
    public function show(Category $category)
    {
        return $this->returnData(__('messages.category.details'),200,
            new CategoryResource($this->categoryService->show($category)));
    }
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categoryService->update($request,$category);
        return $this->success(__('messages.category.updated'),200);
    }
    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);
        return $this->success(__('messages.category.deleted'),200);
    }

}
