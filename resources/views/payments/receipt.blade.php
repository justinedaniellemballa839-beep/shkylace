@extends('layouts.app')
@section('title', 'Reçu')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-shky text-center">
                <div class="card-body p-5">
                    <i class="bi bi-check-circle-fill text-success fs-1 mb-3"></i>
                    <h4 class="fw-bold">Paiement confirmé</h4>
                    <p class="text-muted">Reçu N° {{ $payment->receipt->receipt_number ?? '-' }}</p>
                    <hr>
                    <p><strong>Référence:</strong> {{ $payment->reference }}</p>
                    <p><strong>Montant:</strong> {{ number_format($payment->amount, 0, ',', ' ') }} FCFA</p>
                    <p><strong>Méthode:</strong> {{ $payment->method }}</p>
                    @if($payment->order)
                        <p><strong>Commande:</strong> {{ $payment->order->order_number }}</p>
                    @endif
                    <p class="small text-muted">{{ now()->format('d/m/Y H:i') }}</p>
                    <button onclick="window.print()" class="btn btn-shky mt-3"><i class="bi bi-printer me-2"></i>Imprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
