@extends('layouts.app')
@section('title', 'Créer boutique')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-shky form-shky">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Créer ma boutique</h4>
                    <form method="POST" action="{{ route('vendor.shop.store') }}">
                        @csrf
                        <div class="mb-3"><label>Nom de la boutique</label><input type="text" name="name" class="form-control" required></div>
                        <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
                        <div class="mb-3">
                            <label>Marché</label>
                            <select name="market_id" class="form-select">
                                <option value="">-- Sélectionner --</option>
                                @foreach($markets as $market)
                                    <option value="{{ $market->id }}">{{ $market->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label>Adresse</label><input type="text" name="address" class="form-control" required></div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label>Quartier</label><input type="text" name="quartier" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Ville</label><input type="text" name="ville" class="form-control" required></div>
                        </div>
                        <div class="mb-3"><label>Téléphone boutique</label><input type="tel" name="phone" class="form-control" required></div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label>Latitude</label><input type="number" step="any" name="latitude" class="form-control"></div>
                            <div class="col-md-6 mb-3"><label>Longitude</label><input type="number" step="any" name="longitude" class="form-control"></div>
                        </div>
                        <button type="submit" class="btn btn-shky">Créer la boutique</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
