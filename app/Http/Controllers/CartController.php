<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.shop']);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'integer|min:1']);

        $quantity = $request->input('quantity', 1);

        if ($product->quantity < $quantity) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $cart = $this->getOrCreateCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->update(['quantity' => $item->quantity + $quantity]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->final_price,
            ]);
        }

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request $request, CartItem $item)
    {
        if ($item->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['quantity' => 'required|integer|min:1']);

        if ($item->product->quantity < $request->quantity) {
            return back()->with('error', 'Stock insuffisant.');
        }

        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Quantité mise à jour.');
    }

    public function remove(CartItem $item)
    {
        if ($item->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Article retiré du panier.');
    }

    private function getOrCreateCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }
}
