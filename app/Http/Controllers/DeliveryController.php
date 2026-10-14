<?php

namespace App\Http\Controllers;

use App\Models\DeliveryApplication;
use App\Models\DeliveryTracking;
use App\Models\LivreurCommission;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    public function dashboard()
    {
        $livreur = auth()->user();
        $deliveries = Order::where('livreur_id', $livreur->id)
            ->with(['shop', 'user'])
            ->latest()
            ->paginate(10);

        $commissions = LivreurCommission::where('livreur_id', $livreur->id)
            ->sum('total_commission');

        $stats = [
            'active' => $deliveries->whereIn('status', ['en_cours_livraison', 'expediee'])->count(),
            'completed' => Order::where('livreur_id', $livreur->id)->where('status', 'livree')->count(),
            'commissions' => $commissions,
        ];

        return view('delivery.dashboard', compact('deliveries', 'stats'));
    }

    public function updateLocation(Request $request, Order $order)
    {
        if ($order->livreur_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'estimated_minutes' => 'nullable|integer',
        ]);

        DeliveryTracking::create([
            'order_id' => $order->id,
            'livreur_id' => auth()->id(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $order->status,
            'estimated_minutes' => $request->estimated_minutes,
        ]);

        auth()->user()->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['success' => true]);
    }

    public function completeDelivery(Request $request, Order $order)
    {
        if ($order->livreur_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['rating' => 'nullable|integer|min:1|max:5']);

        $order->update(['status' => 'livree']);

        $commissionData = LivreurCommission::calculate(
            (float) $order->delivery_fee,
            $request->boolean('punctual', true),
            $request->input('rating', 5)
        );

        LivreurCommission::create(array_merge($commissionData, [
            'livreur_id' => auth()->id(),
            'order_id' => $order->id,
            'client_rating' => $request->input('rating', 5),
            'was_punctual' => $request->boolean('punctual', true),
        ]));

        return back()->with('success', 'Livraison terminée.');
    }

    public function showApply()
    {
        return view('delivery.apply');
    }

    public function apply(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'quartier' => 'required|string',
            'ville' => 'required|string',
            'emergency_contact' => 'required|string',
            'cover_letter' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'photo' => 'required|image|max:2048',
            'id_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DeliveryApplication::create([
            'user_id' => auth()->id(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'quartier' => $validated['quartier'],
            'ville' => $validated['ville'],
            'emergency_contact' => $validated['emergency_contact'],
            'cover_letter' => $validated['cover_letter'] ?? null,
            'cv_path' => $request->file('cv')->store('applications/cv', 'public'),
            'photo_path' => $request->file('photo')->store('applications/photos', 'public'),
            'id_card_path' => $request->file('id_card')->store('applications/id_cards', 'public'),
        ]);

        return redirect()->route('home')
            ->with('success', 'Candidature envoyée. Nous vous contacterons bientôt.');
    }

    public function availableLivreurs(Order $order)
    {
        $shop = auth()->user()->shop;
        if ($order->shop_id !== $shop->id) {
            abort(403);
        }

        $livreurs = User::where('role', 'livreur')
            ->where('is_active', true)
            ->whereNotNull('latitude')
            ->get();

        return view('vendor.orders.assign-livreur', compact('order', 'livreurs'));
    }
}
