<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('vendor.shop.create')
                ->with('warning', 'Créez d\'abord votre boutique.');
        }

        $products = $shop->products()->with('category')->latest()->paginate(10);

        return view('vendor.products.index', compact('products', 'shop'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('vendor.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $shop = auth()->user()->shop;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['shop_id'] = $shop->id;
        Product::create($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Produit ajouté au catalogue.');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);
        $categories = \App\Models\Category::all();
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'is_available' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Produit supprimé.');
    }

    private function authorizeProduct(Product $product): void
    {
        if ($product->shop->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
