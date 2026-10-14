@extends('layouts.app')
@section('title', $tontine->title)
@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h2 class="section-title">{{ $tontine->title }}</h2>
            <p class="text-muted">{{ $tontine->description }}</p>
            <div class="card card-shky mb-4">
                <div class="card-body">
                    <h6 class="fw-bold">Progression</h6>
                    <div class="progress-shky my-2"><div class="progress-bar" style="width: {{ $tontine->progress_percent }}%"></div></div>
                    <p>{{ number_format($tontine->collected_amount, 0, ',', ' ') }} / {{ number_format($tontine->target_amount, 0, ',', ' ') }} FCFA ({{ $tontine->progress_percent }}%)</p>
                    @if($tontine->isComplete())<span class="badge bg-success">Objectif atteint !</span>@endif
                </div>
            </div>
            <h5 class="fw-bold">Produits visés</h5>
            @foreach($tontine->products as $tp)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>{{ $tp->product->name ?? 'Produit' }} × {{ $tp->quantity }}</span>
                    <span>{{ number_format($tp->unit_price * $tp->quantity, 0, ',', ' ') }} F</span>
                </div>
            @endforeach
            <h5 class="fw-bold mt-4">Historique des cotisations</h5>
            @forelse($tontine->contributions as $c)
                <div class="d-flex justify-content-between py-2 border-bottom small">
                    <span>{{ $c->user->full_name }} · {{ $c->payment_method }}</span>
                    <span>{{ number_format($c->amount, 0, ',', ' ') }} F · {{ $c->created_at->format('d/m/Y') }}</span>
                </div>
            @empty
                <p class="text-muted">Aucune cotisation encore</p>
            @endforelse
        </div>
        <div class="col-md-4">
            @auth
                <div class="card card-shky form-shky">
                    <div class="card-body">
                        <h6 class="fw-bold">Cotiser</h6>
                        <form action="{{ route('tontines.contribute', $tontine) }}" method="POST">
                            @csrf
                            <div class="mb-3"><label>Montant (FCFA)</label><input type="number" name="amount" class="form-control" min="100" required></div>
                            <div class="mb-3">
                                <label>Mobile Money</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="mtn_money">MTN Money</option>
                                    <option value="orange_money">Orange Money</option>
                                    <option value="blue_money">Blue Money</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-shky w-100">Cotiser</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
