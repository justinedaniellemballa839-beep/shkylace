@extends('layouts.app')
@section('title', 'Mes produits')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between mb-4">
        <h2 class="section-title mb-0">Mes produits</h2>
        <a href="{{ route('vendor.products.create') }}" class="btn btn-shky"><i class="bi bi-plus"></i> Ajouter</a>
    </div>
    <table class="table table-shky">
        <thead><tr><th>Produit</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ number_format($product->price, 0, ',', ' ') }} F</td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        <a href="{{ route('vendor.products.edit', $product) }}" class="btn btn-sm btn-shky-outline">Modifier</a>
                        <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" class="d-inline">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection
