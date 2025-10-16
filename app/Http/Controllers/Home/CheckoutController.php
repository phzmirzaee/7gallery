<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show():View
    {
        $products=json_decode(Cookie::get('basket'),true) ?? [];
        $productsPrice=array_sum(array_column($products, 'price'));
        return view('frontend.checkout',compact('products','productsPrice'));
    }
}
