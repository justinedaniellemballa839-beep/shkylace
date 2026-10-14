@extends('layouts.app')
@section('title', 'Marchés')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Nos marchés locaux</h2>
    <div class="row g-4">
        @foreach($markets as $market)
            <div class="col-md-4">
                <div class="card card-shky h-100">
                    <div class="product-placeholder"><i class="bi bi-shop"></i></div>
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $market->name }}</h5>
                        <p class="text-muted"><i class="bi bi-geo-alt"></i> {{ $market->quartier }}, {{ $market->ville }}</p>
                        <p>{{ $market->description }}</p>
                        <a href="{{ route('markets.show', $market) }}" class="btn btn-shky btn-sm">Explorer</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $markets->links() }}
</div>
@endsection
