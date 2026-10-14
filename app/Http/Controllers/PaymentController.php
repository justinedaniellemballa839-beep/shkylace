<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payments.checkout', compact('order'));
    }

    public function process(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'method' => 'required|in:mtn_money,orange_money,blue_money',
            'phone' => 'required|string|min:9',
        ]);

        $payment = DB::transaction(function () use ($validated, $order) {
            $payment = Payment::create([
                'reference' => Payment::generateReference(),
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'amount' => $order->total,
                'method' => $validated['method'],
                'phone' => $validated['phone'],
                'status' => $this->validateTransaction($validated) ? 'confirmed' : 'suspicious',
                'metadata' => ['ip' => request()->ip()],
            ]);

            if ($payment->status === 'confirmed') {
                $order->update(['status' => 'confirmee']);

                Receipt::create([
                    'payment_id' => $payment->id,
                    'receipt_number' => Receipt::generateNumber(),
                    'details' => [
                        'order_number' => $order->order_number,
                        'amount' => $order->total,
                        'method' => $validated['method'],
                        'client' => auth()->user()->full_name,
                        'shop' => $order->shop->name,
                        'date' => now()->toDateTimeString(),
                    ],
                ]);
            }

            return $payment;
        });

        if ($payment->status === 'suspicious') {
            return back()->with('error', 'Transaction suspecte détectée. Contactez le support.');
        }

        return redirect()->route('payments.receipt', $payment)
            ->with('success', 'Paiement confirmé.');
    }

    public function receipt(Payment $payment)
    {
        if ($payment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $payment->load(['receipt', 'order.shop']);

        return view('payments.receipt', compact('payment'));
    }

    private function validateTransaction(array $data): bool
    {
        return strlen($data['phone']) >= 9;
    }
}
