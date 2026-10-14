@extends('layouts.app')
@section('title', 'Commande ' . $order->order_number)
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">Commande {{ $order->order_number }}</h2>
        <span class="badge-status badge-{{ $order->status }} fs-6">{{ $order->status_label }}</span>
    </div>
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card card-shky">
                <div class="card-body">
                    <h5 class="fw-bold">Articles</h5>
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                            <span>{{ number_format($item->total_price, 0, ',', ' ') }} F</span>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between py-2"><span>Sous-total</span><span>{{ number_format($order->subtotal, 0, ',', ' ') }} F</span></div>
                    <div class="d-flex justify-content-between py-2"><span>Livraison</span><span>{{ number_format($order->delivery_fee, 0, ',', ' ') }} F</span></div>
                    @if($order->discount > 0)
                        <div class="d-flex justify-content-between py-2 text-success"><span>Réduction</span><span>-{{ number_format($order->discount, 0, ',', ' ') }} F</span></div>
                    @endif
                    <div class="d-flex justify-content-between py-2 fw-bold fs-5"><span>Total</span><span style="color:var(--sp-primary)">{{ number_format($order->total, 0, ',', ' ') }} FCFA</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-shky mb-3">
                <div class="card-body">
                    <h6 class="fw-bold">Livraison</h6>
                    <p class="mb-1"><strong>Type:</strong> {{ $order->delivery_type === 'domicile' ? 'À domicile' : 'Pickup' }}</p>
                    @if($order->delivery_address)<p class="mb-1"><strong>Adresse:</strong> {{ $order->delivery_address }}</p>@endif
                    @if($order->delivery_date)<p class="mb-1"><strong>Date:</strong> {{ $order->delivery_date->format('d/m/Y') }}</p>@endif
                    @if($order->livreur)
                        <p class="mb-0"><strong>Livreur:</strong> {{ $order->livreur->full_name }}</p>
                    @endif
                </div>
            </div>
            @if(in_array($order->status, ['en_attente', 'confirmee']))
                <form action="{{ route('orders.cancel', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Annuler cette commande ?')">Annuler</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
