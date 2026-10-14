@extends('layouts.app')
@section('title', 'Panier')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4"><i class="bi bi-cart3 me-2"></i>Mon panier</h2>
    <div class="row">
        <div class="col-lg-8">
            @if($cart->items->isEmpty())
                <div class="card card-shky text-center py-5">
                    <i class="bi bi-cart-x fs-1 text-muted"></i>
                    <p class="text-muted mt-3">Votre panier est vide</p>
                    <a href="{{ route('products.index') }}" class="btn btn-shky">Continuer les achats</a>
                </div>
            @else
                @foreach($cart->items as $item)
                    <div class="card card-shky mb-3">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $item->product->name }}</h6>
                                <p class="text-muted small mb-0">{{ $item->product->shop->name }}</p>
                                <span class="price">{{ number_format($item->unit_price, 0, ',', ' ') }} F × {{ $item->quantity }}</span>
                            </div>
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                @csrf @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm" style="width:70px">
                                <button type="submit" class="btn btn-sm btn-shky-outline"><i class="bi bi-arrow-repeat"></i></button>
                            </form>
                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="col-lg-4">
            <div class="cart-summary">
                <h5 class="fw-bold mb-3">Récapitulatif</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Sous-total</span>
                    <span>{{ number_format($cart->subtotal ?? 0, 0, ',', ' ') }} F</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Frais de livraison (estimés)</span>
                    <span>{{ number_format($cart->delivery_fee ?? 0, 0, ',', ' ') }} F</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between total-row">
                    <span>Total</span>
                    <span>{{ number_format($cart->total ?? 0, 0, ',', ' ') }} FCFA</span>
                </div>
                @if(!$cart->items->isEmpty())
                    <form action="{{ route('orders.checkout') }}" method="POST" class="mt-4 form-shky">
                        @csrf
                        <div class="mb-3">
                            <label>Type de livraison</label>
                            <select name="delivery_type" class="form-select" required>
                                <option value="domicile">Livraison à domicile</option>
                                <option value="pickup">Retrait en boutique (pickup)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Adresse de livraison</label>
                            <input type="text" name="delivery_address" class="form-control" placeholder="Votre adresse">
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Date</label>
                                <input type="date" name="delivery_date" class="form-control">
                            </div>
                            <div class="col-6 mb-3">
                                <label>Heure</label>
                                <input type="time" name="delivery_time" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-shky w-100">Valider la commande</button>
                        <p class="small text-muted text-center mt-2"><i class="bi bi-shield-lock"></i> PIN requis pour validation</p>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
