<?php

namespace App\Services\v1\Author\Category;

use App\Repositories\v1\Interface\Author\Category\ICategory;
use App\Traits\ImageTrait;

class CategoryService
{
    use ImageTrait;
    protected ICategory $category;

    public function __construct(ICategory $category)
    {
        $this->category = $category;
    }

    public function index($request,$limit)
    {
        $query = $request->input('query');
        return $this->category->get($query,$limit);
    }

    public function store($request)
    {
        $image = $this->saveImage($request,'image','Category/Images');
        $category = [
            'name' => $request->name,
            'image' => $image,
        ];
        return $this->category->store($category);
    }
    public function show($category)
    {
        return $this->category->show($category);
    }


    public function update($request, $category)
    {
        $image = $this->updateImage($request,'image','Category/Images',$category->image);
        $category->name = $request->name ?? $category->name;
        $category->image = $image ?? $category->image;

        return $this->category->update($category);
    }

    public function delete($category)
    {
        $this->deleteImage($category->image);
        return $this->category->delete($category);
    }
}
