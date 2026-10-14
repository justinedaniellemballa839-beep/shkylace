<?php

namespace App\Http\Controllers;

use App\Models\Tontine;
use App\Models\TontineContribution;
use App\Models\TontineMember;
use App\Models\TontineProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TontineController extends Controller
{
    public function index()
    {
        $tontines = Tontine::with(['shop', 'members'])
            ->where('status', 'active')
            ->latest()
            ->paginate(10);

        $myTontines = auth()->check()
            ? TontineMember::where('user_id', auth()->id())->with('tontine.shop')->get()
            : collect();

        return view('tontines.index', compact('tontines', 'myTontines'));
    }

    public function show(Tontine $tontine)
    {
        $tontine->load(['shop', 'members.user', 'products.product', 'contributions.user']);
        return view('tontines.show', compact('tontine'));
    }

    public function create()
    {
        $shop = auth()->user()->shop;
        $products = $shop->products()->where('is_available', true)->get();
        return view('vendor.tontines.create', compact('products', 'shop'));
    }

    public function store(Request $request)
    {
        $shop = auth()->user()->shop;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:1000',
            'deadline' => 'nullable|date|after:today',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated, $shop) {
            $tontine = Tontine::create([
                'shop_id' => $shop->id,
                'created_by' => auth()->id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'target_amount' => $validated['target_amount'],
                'deadline' => $validated['deadline'] ?? null,
            ]);

            foreach ($validated['products'] as $item) {
                $product = $shop->products()->findOrFail($item['id']);
                TontineProduct::create([
                    'tontine_id' => $tontine->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->final_price,
                ]);
            }
        });

        return redirect()->route('vendor.tontines.index')
            ->with('success', 'Tontine créée avec succès.');
    }

    public function invite(Request $request, Tontine $tontine)
    {
        $this->authorizeTontine($tontine);

        $request->validate(['phone' => 'required|string']);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->with('error', 'Utilisateur non trouvé avec ce numéro.');
        }

        TontineMember::firstOrCreate(
            ['tontine_id' => $tontine->id, 'user_id' => $user->id],
            ['status' => 'invited']
        );

        return back()->with('success', 'Membre invité.');
    }

    public function contribute(Request $request, Tontine $tontine)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:mtn_money,orange_money,blue_money',
        ]);

        $contribution = TontineContribution::create([
            'tontine_id' => $tontine->id,
            'user_id' => auth()->id(),
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'confirmed',
            'transaction_ref' => 'TNT-' . strtoupper(substr(uniqid(), -8)),
        ]);

        $tontine->increment('collected_amount', $validated['amount']);

        TontineMember::updateOrCreate(
            ['tontine_id' => $tontine->id, 'user_id' => auth()->id()],
            ['status' => 'active']
        )->increment('contributed_amount', $validated['amount']);

        if ($tontine->fresh()->isComplete()) {
            $tontine->update(['status' => 'completed']);
        }

        return back()->with('success', 'Cotisation enregistrée.');
    }

    public function vendorIndex()
    {
        $shop = auth()->user()->shop;
        $tontines = $shop->tontines()->with('members')->latest()->paginate(10);
        return view('vendor.tontines.index', compact('tontines'));
    }

    private function authorizeTontine(Tontine $tontine): void
    {
        if ($tontine->shop->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
