@extends('layouts.app')
@section('title', 'Modifier produit')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-shky form-shky">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Modifier : {{ $product->name }}</h4>
                    <form method="POST" action="{{ route('vendor.products.update', $product) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-3"><label>Nom</label><input type="text" name="name" class="form-control" value="{{ $product->name }}" required></div>
                        <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="3" required>{{ $product->description }}</textarea></div>
                        <div class="mb-3">
                            <label>Catégorie</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3"><label>Prix</label><input type="number" name="price" class="form-control" value="{{ $product->price }}" required></div>
                            <div class="col-md-4 mb-3"><label>Quantité</label><input type="number" name="quantity" class="form-control" value="{{ $product->quantity }}" required></div>
                            <div class="col-md-4 mb-3"><label>Unité</label><input type="text" name="unit" class="form-control" value="{{ $product->unit }}"></div>
                        </div>
                        <div class="mb-3"><label>Réduction (%)</label><input type="number" name="discount_percent" class="form-control" value="{{ $product->discount_percent }}"></div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_available" value="1" class="form-check-input" id="avail" {{ $product->is_available ? 'checked' : '' }}>
                            <label class="form-check-label" for="avail">Disponible</label>
                        </div>
                        <div class="mb-3"><label>Nouvelle image</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                        <button type="submit" class="btn btn-shky">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
