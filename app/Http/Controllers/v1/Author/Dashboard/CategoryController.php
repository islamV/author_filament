<?php

namespace App\Http\Controllers\v1\Author\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\author\Category\StoreCategoryRequest;
use App\Http\Requests\v1\author\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\ImageTrait;

class CategoryController extends Controller
{
    use ImageTrait;
    public function list()
    {
        $categories = Category::orderBy('created_at','desc')->paginate(10);
        return view('pages.category.index' , compact('categories'));
    }

    public function create()
    {
        return view('pages.category.add');
    }

    public function store(StoreCategoryRequest $request)
    {
        $image = $this->saveImage($request,'image','Categories/Images');
        $category = [
            "name" => $request->name,
            "image" => $image,
        ];
        Category::create($category);
        return redirect()->route('categories.list')->with('status' , 'Category Added Successfully');
    }
    public function edit(Category $category)
    {
        return view('pages.category.edit' , compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $imagePath  = $this->updateImage($request,'image', 'Categories/Images' , $category->image);
        $category->update([
            'name' => $request->name ?? $category->name,
            'image' => $imagePath ?? $category->image,
        ]);
        return redirect()->route('categories.list')->with('status' , 'Category Updated Successfully');
    }

    public function delete(Category $category)
    {
        $this->deleteImage($category->image);
        $category->delete();
        return redirect()->route('categories.list')->with('status' , 'Category Deleted Successfully');
    }
}
