@extends('layouts.app')
@section('title', 'Candidatures livreurs')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Candidatures livreurs</h2>
    @foreach($applications as $app)
        <div class="card card-shky mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="fw-bold">{{ $app->first_name }} {{ $app->last_name }}</h5>
                        <p class="mb-1"><i class="bi bi-envelope"></i> {{ $app->email }} · <i class="bi bi-phone"></i> {{ $app->phone }}</p>
                        <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $app->quartier }}, {{ $app->ville }}</p>
                        <p class="small text-muted">Contact urgence: {{ $app->emergency_contact }}</p>
                    </div>
                    <span class="badge bg-{{ $app->status === 'pending' ? 'warning' : ($app->status === 'approved' ? 'success' : 'danger') }}">{{ $app->status }}</span>
                </div>
                @if($app->status === 'pending')
                    <form action="{{ route('admin.delivery.review', $app) }}" method="POST" class="mt-3 d-flex gap-2">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button class="btn btn-sm btn-success">Approuver</button>
                    </form>
                    <form action="{{ route('admin.delivery.review', $app) }}" method="POST" class="mt-2 d-flex gap-2">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button class="btn btn-sm btn-outline-danger">Rejeter</button>
                    </form>
                @endif
            </div>
        </div>
    @endforeach
    {{ $applications->links() }}
</div>
@endsection
