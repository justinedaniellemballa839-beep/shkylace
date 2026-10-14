<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\Shop;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function dashboard()
    {
        $shop = auth()->user()->shop;
        $stats = [
            'products_count' => $shop?->products()->count() ?? 0,
            'orders_pending' => $shop?->orders()->where('status', 'en_attente')->count() ?? 0,
            'orders_total' => $shop?->orders()->count() ?? 0,
            'revenue' => $shop?->orders()->whereIn('status', ['livree', 'confirmee'])->sum('total') ?? 0,
            'tontines_active' => $shop?->tontines()->where('status', 'active')->count() ?? 0,
        ];

        $recentOrders = $shop?->orders()->with('user')->latest()->take(5)->get() ?? collect();

        return view('vendor.dashboard', compact('shop', 'stats', 'recentOrders'));
    }

    public function createShop()
    {
        if (auth()->user()->shop) {
            return redirect()->route('vendor.dashboard');
        }

        $markets = Market::where('is_active', true)->get();
        return view('vendor.shop.create', compact('markets'));
    }

    public function storeShop(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'market_id' => 'nullable|exists:markets,id',
            'address' => 'required|string',
            'quartier' => 'required|string',
            'ville' => 'required|string',
            'phone' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Shop::create($validated);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Boutique créée. En attente de validation.');
    }
}
