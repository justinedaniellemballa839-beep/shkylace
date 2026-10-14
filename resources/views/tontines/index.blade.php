@extends('layouts.app')
@section('title', 'Tontines')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4"><i class="bi bi-people me-2"></i>Tontines digitales</h2>
    <p class="text-muted mb-4">Participez à des achats groupés et cotisez via Mobile Money</p>
    <div class="row g-4">
        @foreach($tontines as $tontine)
            <div class="col-md-6">
                <div class="card card-shky h-100">
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $tontine->title }}</h5>
                        <p class="text-muted small">{{ $tontine->shop->name }}</p>
                        <div class="progress-shky"><div class="progress-bar" style="width: {{ $tontine->progress_percent }}%"></div></div>
                        <p class="small mt-2">{{ number_format($tontine->collected_amount, 0, ',', ' ') }} / {{ number_format($tontine->target_amount, 0, ',', ' ') }} FCFA</p>
                        <a href="{{ route('tontines.show', $tontine) }}" class="btn btn-shky btn-sm">Voir détails</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $tontines->links() }}
</div>
@endsection
