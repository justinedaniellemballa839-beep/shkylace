@extends('layouts.app')
@section('title', $market->name)
@section('content')
<div class="container py-5">
    <h2 class="section-title">{{ $market->name }}</h2>
    <p class="text-muted"><i class="bi bi-geo-alt"></i> {{ $market->quartier }}, {{ $market->ville }}</p>
    <p>{{ $market->description }}</p>
    <h4 class="fw-bold mt-4 mb-3">Boutiques</h4>
    <div class="row g-4">
        @foreach($market->shops as $shop)
            <div class="col-md-6">
                <div class="card card-shky">
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $shop->name }}</h5>
                        <p class="text-muted small">{{ $shop->address }}</p>
                        <p class="small">{{ $shop->products->count() }} produit(s)</p>
                        @foreach($shop->products->take(3) as $product)
                            <a href="{{ route('products.show', $product) }}" class="d-block small">{{ $product->name }} - {{ number_format($product->final_price, 0, ',', ' ') }} F</a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
