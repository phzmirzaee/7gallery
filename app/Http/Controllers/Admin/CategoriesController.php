<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreRequest $request)
    {
       $validatedData= $request->validated();
        $created_category=Category::create([
            'title'=>$validatedData['title'],
            'slug'=> $validatedData['slug'],
        ]);
        if(!$created_category){
            return back()->with('error','دسته بندی ایجاد نشد');
        }
        return back()->with('success','دسته بندی ایجاد شد');
    }

    public function all()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.all',compact('categories'));
    }
}
