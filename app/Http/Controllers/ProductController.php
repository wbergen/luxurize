<?php

namespace App\Http\Controllers;

use App\Models\Products\Category;
use App\Models\Products\Product;
use App\Models\Products\Tag;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function tagView(Request $request, $tag_id = null): View
    {
        if (!is_null($tag_id)) {
            $tag = Tag::find($tag_id);
            $products = $tag?->products()?->get() ?? new Collection();
        } else {
            $products = Product::all();
        }
        return view('product-list', ['products' => $products, 'categories' => Category::all(), 'tags' => Tag::all(), 'breadcrumbs' => [
            'tags' => (object)['label' => sprintf('Tag: %s', $tag->name), 'link' => sprintf('/products/tags/%d', $tag->id)]
        ]]);
    }

    public function categoryView(Request $request, $category_id = null): View
    {
        if (!is_null($category_id)) {
            $category = Category::find($category_id);
            $products = $category?->products()?->get() ?? new Collection();
        } else {
            $products = Product::all();
        }
        return view('product-list', ['products' => $products, 'tags' => Tag::all(), 'categories' => Category::all(), 'breadcrumbs' => [
            'categories' => (object)['label' => sprintf('Category: %s', $category->label), 'link' => sprintf('/products/categories/%d', $category->id)]
        ]]);
    }

    public function listView(Request $request): View
    {
        return view('product-list', ['products' => Product::all(), 'tags' => Tag::all(), 'categories' => Category::all(), 'breadcrumbs' => []]);
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

        $product = Product::find($validated['id']);
        if (empty($product)) {
            return ['succeses' => false];
        }

        $cart = ShoppingCart::where('session_id', session()->getId())->get()->first();
        if (empty($cart)) {
            $cart = new ShoppingCart(['session_id' => session()->getId()]);
            $cart->save();
        }
        $product->shoppingCarts()->save($cart);

        return [
            'debug' => [
                'user' => Auth::user()->email,
                'cart' => $cart,
            ]
        ];
    }
}
