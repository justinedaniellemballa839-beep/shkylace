@extends('layouts.app')
@section('title', 'Administration')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4"><i class="bi bi-shield-check me-2"></i>Tableau de bord administrateur</h2>
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-value">{{ $stats['users_count'] }}</div><div class="stat-label">Utilisateurs</div></div></div>
        <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-value">{{ number_format($stats['revenue'], 0, ',', ' ') }}</div><div class="stat-label">CA (FCFA)</div></div></div>
        <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-value">{{ $stats['orders_count'] }}</div><div class="stat-label">Commandes</div></div></div>
        <div class="col-md-3 col-6"><div class="stat-card"><div class="stat-value">{{ $stats['pending_vendors'] }}</div><div class="stat-label">Vendeurs en attente</div></div></div>
    </div>
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card card-shky">
                <div class="card-body">
                    <h5 class="fw-bold">Commandes récentes</h5>
                    <table class="table table-sm mt-3">
                        <thead><tr><th>N°</th><th>Client</th><th>Boutique</th><th>Total</th><th>Statut</th></tr></thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->user->full_name }}</td>
                                    <td>{{ $order->shop->name }}</td>
                                    <td>{{ number_format($order->total, 0, ',', ' ') }} F</td>
                                    <td><span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="sidebar">
                <h6 class="fw-bold">Actions rapides</h6>
                <nav class="nav flex-column">
                    <a class="nav-link" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Utilisateurs</a>
                    <a class="nav-link" href="{{ route('admin.vendors.index') }}"><i class="bi bi-shop me-2"></i>Vendeurs</a>
                    <a class="nav-link" href="{{ route('admin.tontines.index') }}"><i class="bi bi-piggy-bank me-2"></i>Tontines</a>
                    <a class="nav-link" href="{{ route('admin.delivery.index') }}"><i class="bi bi-truck me-2"></i>Candidatures livreurs</a>
                </nav>
            </div>
            <div class="card card-shky mt-3">
                <div class="card-body">
                    <h6 class="fw-bold">Revenus par marché</h6>
                    @foreach($revenueByMarket as $rm)
                        <div class="d-flex justify-content-between py-1"><span>{{ $rm->name }}</span><span>{{ number_format($rm->total, 0, ',', ' ') }} F</span></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
