@extends('layouts.app')
@section('title', 'Mon compte')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Bonjour, {{ $user->first_name }} !</h2>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link active" href="{{ route('dashboard') }}"><i class="bi bi-person me-2"></i>Profil</a>
                    <a class="nav-link" href="{{ route('orders.index') }}"><i class="bi bi-bag me-2"></i>Commandes</a>
                    <a class="nav-link" href="{{ route('cart.index') }}"><i class="bi bi-cart me-2"></i>Panier</a>
                    <a class="nav-link" href="{{ route('password.change') }}"><i class="bi bi-key me-2"></i>Sécurité</a>
                    <a class="nav-link" href="{{ route('tontines.index') }}"><i class="bi bi-people me-2"></i>Tontines</a>
                </nav>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-shky">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informations du compte</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3"><strong>Nom:</strong> {{ $user->full_name }}</div>
                        <div class="col-md-6 mb-3"><strong>Email:</strong> {{ $user->email }}</div>
                        <div class="col-md-6 mb-3"><strong>Téléphone:</strong> {{ $user->phone }}</div>
                        <div class="col-md-6 mb-3"><strong>Rôle:</strong> <span class="badge bg-primary">{{ ucfirst($user->role) }}</span></div>
                        <div class="col-md-6 mb-3"><strong>Ville:</strong> {{ $user->ville ?? '-' }}</div>
                        <div class="col-md-6 mb-3"><strong>Quartier:</strong> {{ $user->quartier ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
