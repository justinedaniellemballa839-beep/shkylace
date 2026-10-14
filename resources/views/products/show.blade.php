@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card card-shky">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height:350px;object-fit:cover" alt="{{ $product->name }}">
                @else
                    <div class="product-placeholder" style="height:350px"><i class="bi bi-image fs-1"></i></div>
                @endif
            </div>
        </div>
        <div class="col-md-7">
            <span class="badge-category">{{ $product->category->name }}</span>
            <h2 class="fw-bold mt-2">{{ $product->name }}</h2>
            <p class="text-muted">{{ $product->description }}</p>
            <div class="d-flex align-items-center gap-3 my-3">
                @if($product->discount_percent > 0)
                    <span class="text-decoration-line-through text-muted">{{ number_format($product->price, 0, ',', ' ') }} F</span>
                    <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                @endif
                <span class="price fs-3">{{ number_format($product->final_price, 0, ',', ' ') }} FCFA</span>
            </div>
            <ul class="list-unstyled">
                <li><i class="bi bi-shop me-2"></i><strong>Boutique:</strong> {{ $product->shop->name }}</li>
                <li><i class="bi bi-geo-alt me-2"></i><strong>Localisation:</strong> {{ $product->location }}</li>
                <li><i class="bi bi-box me-2"></i><strong>Disponible:</strong> {{ $product->quantity }} {{ $product->unit }}</li>
            </ul>
            @auth
                <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex gap-2 mt-4">
                    @csrf
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="form-control" style="width:80px">
                    <button type="submit" class="btn btn-shky flex-grow-1"><i class="bi bi-cart-plus me-2"></i>Ajouter au panier</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-shky mt-3">Connectez-vous pour commander</a>
            @endauth
        </div>
    </div>
</div>
@endsection
