@extends('layouts.app')
@section('title', 'Carte')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4"><i class="bi bi-geo-alt me-2"></i>Géolocalisation</h2>
    <p class="text-muted mb-4">Localisez les boutiques, vendeurs et livreurs en temps réel</p>
    <div id="map" data-lat="4.0511" data-lng="9.7679" data-shops='@json($shops)'></div>
    <div class="row g-3 mt-4">
        @foreach($shops as $shop)
            <div class="col-md-4">
                <div class="card card-shky">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $shop->name }}</h6>
                        <p class="small text-muted mb-0"><i class="bi bi-geo-alt"></i> {{ $shop->quartier }}, {{ $shop->ville }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key', env('GOOGLE_MAPS_API_KEY', '')) }}&callback=initMapIfPresent" async defer></script>
@endpush
