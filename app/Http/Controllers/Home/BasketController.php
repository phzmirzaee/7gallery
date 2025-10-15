<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cookie;

class BasketController extends Controller
{
    public $minutes = 600;

    private function getBasket(?int $productId = null): array
    {
        $basket = json_decode(Cookie::get('basket'), true);
        if (!$basket) {
            $basket = [];
        }
        $product = null;
        if ($productId !== null) {
            $product = Product::findOrFail($productId);
        }

        return ['product' => $product, 'basket' => $basket];

    }

    public function addToBasket(int $productId): RedirectResponse
    {
        $data = $this->getBasket($productId);
        $product = $data['product'];
        $basket = $data['basket'];

        if (isset($basket[$product->id])) {
            return back()->with('success', 'محصول به سبد خرید اضافه شد');
        }

        $basket[$product->id] = [
            'title' => $product->title,
            'price' => $product->price,
            'demo_url' => $product->demo_url,
        ];

        Cookie::queue('basket', json_encode($basket), $this->minutes);

        return back()->with('success', 'محصول به سبد خرید اضافه شد');
    }


    public function removeFromBasket(int $productId): RedirectResponse
    {
        $data = $this->getBasket(); // doesn't need productId
        $basket = $data['basket'];
        if (isset($basket[$productId])) {
            unset($basket[$productId]);
        }
        Cookie::queue('basket', json_encode($basket), $this->minutes);
        return back()->with('success', "محصول از سبد خرید حذف شد");
    }
}
