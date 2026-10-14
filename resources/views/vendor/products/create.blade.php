@extends('layouts.app')
@section('title', 'Ajouter produit')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-shky form-shky">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Ajouter un produit</h4>
                    <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3"><label>Nom</label><input type="text" name="name" class="form-control" required></div>
                        <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                        <div class="mb-3">
                            <label>Catégorie</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label>Prix (FCFA)</label><input type="number" name="price" class="form-control" min="0" required></div>
                            <div class="col-md-4 mb-3"><label>Quantité</label><input type="number" name="quantity" class="form-control" min="0" required></div>
                            <div class="col-md-4 mb-3"><label>Unité</label><input type="text" name="unit" class="form-control" value="unité"></div>
                        </div>
                        <div class="mb-3"><label>Réduction (%)</label><input type="number" name="discount_percent" class="form-control" min="0" max="100" value="0"></div>
                        <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                        <button type="submit" class="btn btn-shky">Publier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
