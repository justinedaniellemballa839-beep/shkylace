@extends('layouts.app')
@section('title', 'Paiement')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-shky form-shky">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Paiement sécurisé</h4>
                    <p>Commande <strong>{{ $order->order_number }}</strong></p>
                    <p class="price fs-4">{{ number_format($order->total, 0, ',', ' ') }} FCFA</p>
                    <form method="POST" action="{{ route('payments.process', $order) }}">
                        @csrf
                        <div class="mb-3"><label>Numéro Mobile Money</label><input type="tel" name="phone" class="form-control" required></div>
                        <label class="mb-2">Méthode de paiement</label>
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <label class="payment-method w-100">
                                    <input type="radio" name="method" value="mtn_money" class="d-none" required>
                                    <div><i class="bi bi-phone fs-3"></i><br><small>MTN Money</small></div>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="payment-method w-100">
                                    <input type="radio" name="method" value="orange_money" class="d-none">
                                    <div><i class="bi bi-phone fs-3"></i><br><small>Orange Money</small></div>
                                </label>
                            </div>
                            <div class="col-4">
                                <label class="payment-method w-100">
                                    <input type="radio" name="method" value="blue_money" class="d-none">
                                    <div><i class="bi bi-phone fs-3"></i><br><small>Blue Money</small></div>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-shky w-100">Payer maintenant</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
