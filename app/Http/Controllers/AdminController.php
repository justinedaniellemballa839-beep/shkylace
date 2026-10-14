<?php

namespace App\Http\Controllers;

use App\Models\DeliveryApplication;
use App\Models\LivreurCommission;
use App\Models\Market;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shop;
use App\Models\Tontine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'vendors_count' => User::where('role', 'vendeur')->count(),
            'livreurs_count' => User::where('role', 'livreur')->count(),
            'orders_count' => Order::count(),
            'revenue' => Payment::where('status', 'confirmed')->sum('amount'),
            'markets_count' => Market::count(),
            'tontines_active' => Tontine::where('status', 'active')->count(),
            'pending_vendors' => Shop::where('status', 'pending')->count(),
        ];

        $revenueByMarket = Market::select('markets.name')
            ->selectRaw('COALESCE(SUM(payments.amount), 0) as total')
            ->leftJoin('shops', 'shops.market_id', '=', 'markets.id')
            ->leftJoin('orders', 'orders.shop_id', '=', 'shops.id')
            ->leftJoin('payments', function ($join) {
                $join->on('payments.order_id', '=', 'orders.id')
                    ->where('payments.status', '=', 'confirmed');
            })
            ->groupBy('markets.id', 'markets.name')
            ->get();

        $monthlySales = Payment::where('status', 'confirmed')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $recentOrders = Order::with(['user', 'shop'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'revenueByMarket', 'monthlySales', 'recentOrders'));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Statut utilisateur mis à jour.');
    }

    public function vendors()
    {
        $shops = Shop::with('user', 'market')->latest()->paginate(20);
        return view('admin.vendors.index', compact('shops'));
    }

    public function approveVendor(Shop $shop)
    {
        $shop->update(['status' => 'approved']);
        $shop->user->update(['is_verified' => true]);
        return back()->with('success', 'Vendeur approuvé.');
    }

    public function suspendVendor(Shop $shop)
    {
        $shop->update(['status' => 'suspended']);
        return back()->with('success', 'Vendeur suspendu.');
    }

    public function tontines()
    {
        $tontines = Tontine::with(['shop', 'members'])->latest()->paginate(20);
        return view('admin.tontines.index', compact('tontines'));
    }

    public function deliveryApplications()
    {
        $applications = DeliveryApplication::latest()->paginate(20);
        return view('admin.delivery.index', compact('applications'));
    }

    public function reviewApplication(Request $request, DeliveryApplication $application)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        DB::transaction(function () use ($request, $application) {
            $application->update([
                'status' => $request->status,
                'reviewed_by' => auth()->id(),
            ]);

            if ($request->status === 'approved' && $application->user_id) {
                User::where('id', $application->user_id)->update(['role' => 'livreur']);
            }
        });

        return back()->with('success', 'Candidature traitée.');
    }
}
