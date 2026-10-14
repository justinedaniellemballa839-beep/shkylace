<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['shop', 'livreur', 'items'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order->load(['items.product', 'shop', 'livreur', 'tracking']);

        return view('orders.show', compact('order'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'delivery_type' => 'required|in:domicile,pickup',
            'delivery_date' => 'nullable|date|after_or_equal:today',
            'delivery_time' => 'nullable',
            'delivery_address' => 'required_if:delivery_type,domicile|nullable|string',
            'notes' => 'nullable|string',
        ]);

        $cart = auth()->user()->cart()->with('items.product.shop')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $orders = DB::transaction(function () use ($cart, $validated) {
            $createdOrders = [];
            $itemsByShop = $cart->items->groupBy(fn ($item) => $item->product->shop_id);

            foreach ($itemsByShop as $shopId => $items) {
                $subtotal = $items->sum(fn ($i) => $i->unit_price * $i->quantity);
                $discount = $items->sum(fn ($i) => ($i->product->discount_percent / 100) * $i->unit_price * $i->quantity);
                $deliveryFee = 1500;

                foreach ($items as $item) {
                    if ($item->product->quantity < $item->quantity) {
                        throw new \Exception("Stock insuffisant pour {$item->product->name}");
                    }
                }

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => auth()->id(),
                    'shop_id' => $shopId,
                    'status' => 'en_attente',
                    'delivery_type' => $validated['delivery_type'],
                    'delivery_date' => $validated['delivery_date'] ?? null,
                    'delivery_time' => $validated['delivery_time'] ?? null,
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'quartier' => auth()->user()->quartier,
                    'zone' => auth()->user()->quartier,
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'discount' => $discount,
                    'total' => $subtotal + $deliveryFee - $discount,
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->unit_price * $item->quantity,
                    ]);

                    $item->product->decrement('quantity', $item->quantity);
                }

                $createdOrders[] = $order;
            }

            $cart->items()->delete();

            return $createdOrders;
        });

        session()->forget('pin_verified');

        return redirect()->route('orders.index')
            ->with('success', count($orders) . ' commande(s) créée(s) avec succès.');
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($order->status, ['en_attente', 'confirmee'])) {
            return back()->with('error', 'Cette commande ne peut plus être annulée.');
        }

        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)->increment('quantity', $item->quantity);
        }

        $order->update(['status' => 'annulee']);

        return back()->with('success', 'Commande annulée.');
    }

    public function vendorOrders()
    {
        $shop = auth()->user()->shop;
        $orders = Order::where('shop_id', $shop->id)
            ->with(['user', 'livreur', 'items'])
            ->latest()
            ->paginate(15);

        return view('vendor.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $shop = auth()->user()->shop;
        if ($order->shop_id !== $shop->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:confirmee,en_preparation,expediee,en_cours_livraison,livree,annulee',
            'livreur_id' => 'nullable|exists:users,id',
        ]);

        $order->update([
            'status' => $request->status,
            'livreur_id' => $request->livreur_id ?? $order->livreur_id,
        ]);

        return back()->with('success', 'Statut de commande mis à jour.');
    }
}
