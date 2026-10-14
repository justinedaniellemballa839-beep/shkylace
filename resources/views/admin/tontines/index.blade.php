@extends('layouts.app')
@section('title', 'Tontines - Admin')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Supervision des tontines</h2>
    @foreach($tontines as $tontine)
        <div class="card card-shky mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="fw-bold">{{ $tontine->title }}</h5>
                        <p class="text-muted small">{{ $tontine->shop->name }} · {{ $tontine->members->count() }} membre(s)</p>
                    </div>
                    <span class="badge bg-{{ $tontine->status === 'active' ? 'primary' : 'success' }}">{{ $tontine->status }}</span>
                </div>
                <div class="progress-shky mt-2"><div class="progress-bar" style="width: {{ $tontine->progress_percent }}%"></div></div>
                <p class="small mt-1">{{ number_format($tontine->collected_amount, 0, ',', ' ') }} / {{ number_format($tontine->target_amount, 0, ',', ' ') }} FCFA ({{ $tontine->progress_percent }}%)</p>
            </div>
        </div>
    @endforeach
    {{ $tontines->links() }}
</div>
@endsection
