@extends('layouts.app')
@section('title', 'Produits')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="section-title">Catalogue produits</h2>
            <p class="text-muted">Découvrez les offres de nos vendeurs locaux</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="sidebar">
                <h6 class="fw-bold mb-3">Filtrer</h6>
                <form method="GET">
                    <div class="mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="mb-3">
                        <select name="category" class="form-select">
                            <option value="">Toutes catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="quartier" class="form-control" placeholder="Quartier" value="{{ request('quartier') }}">
                    </div>
                    <button type="submit" class="btn btn-shky w-100">Filtrer</button>
                </form>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-4 col-sm-6">
                        <div class="card card-shky h-100">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <div class="product-placeholder"><i class="bi bi-box-seam"></i></div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <span class="badge-category align-self-start">{{ $product->category->name }}</span>
                                <h6 class="fw-bold mt-2">{{ $product->name }}</h6>
                                <p class="text-muted small flex-grow-1">{{ Str::limit($product->description, 60) }}</p>
                                <p class="small mb-1"><i class="bi bi-geo-alt"></i> {{ $product->location }}</p>
                                <p class="small text-muted">Stock: {{ $product->quantity }} {{ $product->unit }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="price">{{ number_format($product->final_price, 0, ',', ' ') }} F</span>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-shky">Détails</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Aucun produit trouvé</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-4">{{ $products->withQueryString()->links() }}</div>
        </div>
    </div>
</div>
@endsection
