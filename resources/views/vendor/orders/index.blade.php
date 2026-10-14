@extends('layouts.app')
@section('title', 'Commandes vendeur')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Commandes reçues</h2>
    @foreach($orders as $order)
        <div class="card card-shky mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="fw-bold">{{ $order->order_number }}</h6>
                        <p class="mb-0 small">Client: {{ $order->user->full_name }} · {{ number_format($order->total, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>
                <form action="{{ route('vendor.orders.status', $order) }}" method="POST" class="mt-3 d-flex gap-2 flex-wrap">
                    @csrf @method('PUT')
                    <select name="status" class="form-select form-select-sm" style="width:auto">
                        <option value="confirmee">Confirmée</option>
                        <option value="en_preparation">En préparation</option>
                        <option value="expediee">Expédiée</option>
                        <option value="en_cours_livraison">En livraison</option>
                        <option value="livree">Livrée</option>
                    </select>
                    <button class="btn btn-sm btn-shky">Mettre à jour</button>
                    <a href="{{ route('vendor.orders.assign', $order) }}" class="btn btn-sm btn-shky-outline">Assigner livreur</a>
                </form>
            </div>
        </div>
    @endforeach
    {{ $orders->links() }}
</div>
@endsection
