<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\StoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Utilities\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;

class ProductController extends Controller
{
    public function all()
    {
        $products = Product::paginate(10);
        return view('admin.products.all', compact('products'));
    }
    public function create()
    {
        $categories=Category::all();
        return view('admin.products.add',compact('categories'));
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();
        $admin = User::where('email', 'admin@gmail.com')->first();

        // ایجاد محصول بدون مسیر عکس‌ها
        $createdProduct = Product::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'owner_id' => $admin->id,
        ]);

        try {
            $basePath = 'products/' . $createdProduct->id . '/';


            // مسیر کامل فایل source
            $sourceImageFullPath = $basePath . 'source_url_' . $validatedData['source_url']->getClientOriginalName();

            // آپلود تصاویر
            $images = [
                'thumbnail_url' => $validatedData['thumbnail_url'],
                'demo_url' => $validatedData['demo_url'],
            ];

            $imagesPath = ImageUploader::uploadMany($images, $basePath);
            ImageUploader::upload($validatedData['source_url'], $sourceImageFullPath, 'local_storage');

            // بروزرسانی مسیرها در دیتابیس
            $updatedProduct=$createdProduct->update([
                'thumbnail_url' => $imagesPath['thumbnail_url'],
                'demo_url' => $imagesPath['demo_url'],
                'source_url' => $sourceImageFullPath,
            ]);
            if(!$updatedProduct){
                throw new Exception('تصاویر اپلود نشدند');
            }
            return back()->with('success', 'محصول با موفقیت ایجاد شد');

        } catch (\Throwable $e) {
         throw $e;
            return back()->with('failed', "aaaaaaaaaaaaaaaaaaa");
        }
    }

    public function downloadDemo($product_id)
    {
        $product=Product::FindOrFail($product_id);
        return response()->download(public_path( $product->demo_url));
    }
    public function downloadSource($product_id){
        $product=Product::FindOrFail($product_id);
        return response()->download(storage_path("app/local_storage/". $product->source_url));
    }

    public function delete($product_id)
    {
        $product=Product::findOrFail($product_id);
        $product->delete();
        return back()->with("success","محصول حذف شد");
    }
}
