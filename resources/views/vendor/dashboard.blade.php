@extends('layouts.app')
@section('title', 'Espace vendeur')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Espace vendeur</h2>
    @if(!$shop)
        <div class="card card-shky text-center py-5">
            <p>Créez votre boutique pour commencer à vendre</p>
            <a href="{{ route('vendor.shop.create') }}" class="btn btn-shky">Créer ma boutique</a>
        </div>
    @else
        <div class="row g-4 mb-4">
            <div class="col-md-3"><div class="stat-card"><div class="stat-value">{{ $stats['products_count'] }}</div><div class="stat-label">Produits</div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="stat-value">{{ $stats['orders_pending'] }}</div><div class="stat-label">En attente</div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="stat-value">{{ number_format($stats['revenue'], 0, ',', ' ') }}</div><div class="stat-label">Revenus FCFA</div></div></div>
            <div class="col-md-3"><div class="stat-card"><div class="stat-value">{{ $stats['tontines_active'] }}</div><div class="stat-label">Tontines actives</div></div></div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="sidebar">
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="{{ route('vendor.dashboard') }}">Tableau de bord</a>
                        <a class="nav-link" href="{{ route('vendor.products.index') }}">Mes produits</a>
                        <a class="nav-link" href="{{ route('vendor.orders.index') }}">Commandes</a>
                        <a class="nav-link" href="{{ route('vendor.tontines.index') }}">Tontines</a>
                    </nav>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-shky">
                    <div class="card-body">
                        <h5>{{ $shop->name }} <span class="badge bg-{{ $shop->status === 'approved' ? 'success' : 'warning' }}">{{ $shop->status }}</span></h5>
                        <p class="text-muted">{{ $shop->address }}, {{ $shop->quartier }}</p>
                        <a href="{{ route('vendor.products.create') }}" class="btn btn-shky btn-sm"><i class="bi bi-plus"></i> Ajouter un produit</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
