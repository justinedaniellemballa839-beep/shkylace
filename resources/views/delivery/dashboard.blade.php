@extends('layouts.app')
@section('title', 'Espace livreur')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Espace livreur</h2>
    <div class="row g-4 mb-4">
        <div class="col-md-4"><div class="stat-card"><div class="stat-value">{{ $stats['active'] }}</div><div class="stat-label">Livraisons actives</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="stat-value">{{ $stats['completed'] }}</div><div class="stat-label">Terminées</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="stat-value">{{ number_format($stats['commissions'], 0, ',', ' ') }}</div><div class="stat-label">Commissions FCFA</div></div></div>
    </div>
    @foreach($deliveries as $order)
        <div class="card card-shky mb-3" id="live-tracking" data-order-id="{{ $order->id }}">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="fw-bold">{{ $order->order_number }}</h6>
                        <p class="small mb-0">{{ $order->shop->name }} → {{ $order->user->full_name }}</p>
                        <p class="small text-muted">{{ $order->delivery_address }}</p>
                    </div>
                    <span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>
                @if(in_array($order->status, ['expediee', 'en_cours_livraison']))
                    <form action="{{ route('delivery.complete', $order) }}" method="POST" class="mt-3">
                        @csrf
                        <button class="btn btn-shky btn-sm">Marquer comme livrée</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
    {{ $deliveries->links() }}
</div>
@endsection
