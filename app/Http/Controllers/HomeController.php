<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Market;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $markets = Market::where('is_active', true)->withCount('shops')->take(6)->get();
        $categories = Category::withCount('products')->get();
        $featuredProducts = Product::with(['shop', 'category'])
            ->where('is_available', true)
            ->where('quantity', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('markets', 'categories', 'featuredProducts'));
    }

    public function markets()
    {
        $markets = Market::where('is_active', true)->with('shops')->paginate(12);
        return view('markets.index', compact('markets'));
    }

    public function marketShow(Market $market)
    {
        $market->load(['shops.products.category']);
        return view('markets.show', compact('market'));
    }

    public function products(Request $request)
    {
        $query = Product::with(['shop', 'category'])
            ->where('is_available', true);

        if ($request->category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->quartier) {
            $query->whereHas('shop', fn ($q) => $q->where('quartier', 'like', "%{$request->quartier}%"));
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function productShow(Product $product)
    {
        $product->load(['shop.market', 'category']);
        return view('products.show', compact('product'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        return view('dashboard', compact('user'));
    }
}
