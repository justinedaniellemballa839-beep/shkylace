@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7 animate-fade-in">
                <h1>Vos marchés locaux,<br>à portée de main</h1>
                <p class="lead mt-3">Découvrez les produits frais, mode, logement et plus encore. Commandez, cotisez en tontine et recevez chez vous.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg fw-semibold px-4">
                        <i class="bi bi-search me-2"></i>Explorer les produits
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">Créer un compte</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <i class="bi bi-basket2" style="font-size: 12rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Catégories populaires</h2>
            <p class="section-subtitle">Trouvez ce dont vous avez besoin près de chez vous</p>
        </div>
        <div class="text-center">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="category-pill">
                    <i class="bi {{ $category->icon ?? 'bi-tag' }}"></i>
                    {{ $category->name }}
                    <span class="badge bg-light text-dark">{{ $category->products_count }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title mb-0">Produits en vedette</h2>
                <p class="section-subtitle mb-0">Les dernières offres de nos vendeurs</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-shky-outline d-none d-md-inline">Voir tout</a>
        </div>
        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card card-shky h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <div class="product-placeholder"><i class="bi bi-image"></i></div>
                        @endif
                        <div class="card-body">
                            <span class="badge-category">{{ $product->category->name ?? '' }}</span>
                            <h6 class="card-title mt-2 fw-bold">{{ $product->name }}</h6>
                            <p class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> {{ $product->location }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price">{{ number_format($product->final_price, 0, ',', ' ') }} FCFA</span>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-shky">Voir</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Aucun produit disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Nos marchés</h2>
            <p class="section-subtitle">Explorez les marchés locaux près de vous</p>
        </div>
        <div class="row g-4">
            @foreach($markets as $market)
                <div class="col-md-4">
                    <div class="card card-shky h-100">
                        <div class="product-placeholder"><i class="bi bi-shop"></i></div>
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $market->name }}</h5>
                            <p class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $market->quartier }}, {{ $market->ville }}</p>
                            <p class="small">{{ $market->shops_count }} boutique(s)</p>
                            <a href="{{ route('markets.show', $market) }}" class="btn btn-shky btn-sm">Visiter</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('markets.index') }}" class="btn btn-shky">Tous les marchés</a>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Pourquoi Shkyplace ?</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-6 text-center">
                <div class="feature-icon mx-auto"><i class="bi bi-shield-lock"></i></div>
                <h6 class="fw-bold">Sécurisé</h6>
                <p class="small text-muted">Mots de passe hachés, PIN pour USSD/SMS</p>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="feature-icon mx-auto"><i class="bi bi-people"></i></div>
                <h6 class="fw-bold">Tontines digitales</h6>
                <p class="small text-muted">Achats groupés via Mobile Money</p>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="feature-icon mx-auto"><i class="bi bi-truck"></i></div>
                <h6 class="fw-bold">Livraison</h6>
                <p class="small text-muted">Suivi en temps réel des livreurs</p>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="feature-icon mx-auto"><i class="bi bi-phone"></i></div>
                <h6 class="fw-bold">Sans internet</h6>
                <p class="small text-muted">Commandes et solde via SMS/USSD</p>
            </div>
        </div>
    </div>
</section>
@endsection
