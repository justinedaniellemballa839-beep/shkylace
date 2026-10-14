<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $shops = Shop::where('status', 'approved')
            ->whereNotNull('latitude')
            ->with('market')
            ->get();

        $livreurs = User::where('role', 'livreur')
            ->where('is_active', true)
            ->whereNotNull('latitude')
            ->get();

        return view('map.index', compact('shops', 'livreurs'));
    }

    public function nearestShop(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $shops = Shop::where('status', 'approved')
            ->whereNotNull('latitude')
            ->get()
            ->map(function ($shop) use ($request) {
                $shop->distance = $this->haversineDistance(
                    $request->latitude,
                    $request->longitude,
                    $shop->latitude,
                    $shop->longitude
                );
                return $shop;
            })
            ->sortBy('distance')
            ->take(5)
            ->values();

        return response()->json($shops);
    }

    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return round($earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a)), 2);
    }
}
