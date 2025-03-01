<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function listView(Request $request): View
    {
        return view('product-list', ['products' => Product::all()]);
    }

    public function detailView(Request $request, $id): View
    {
        $product = Product::where('id', $id)->get()->first();
        if (empty($product)) {
            abort(404);
        }
        return view('product', ['product' => $product]);
    }

    public function addToCart(Request $request): array
    {
        $validated = $request->validate([
            'id' => 'required|numeric',
            'quantity' => 'numeric',
        ]);

        $cartId = $request->session()->get('cartId');
        if (empty($cartId)) {
            $cart = new ShoppingCart();
        }

        return [
            'debug' => [
                'user' => Auth::user()->email,
            ]
        ];
    }
}
