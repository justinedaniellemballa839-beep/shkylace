@extends('layouts.app')
@section('title', 'Vérification PIN')
@section('content')
<div class="auth-wrapper">
    <div class="container">
        <div class="auth-card form-shky text-center">
            <i class="bi bi-shield-lock fs-1 mb-3" style="color: var(--sp-primary);"></i>
            <h4 class="fw-bold">Vérification du code PIN</h4>
            <p class="text-muted">Saisissez votre PIN pour continuer cette opération sensible</p>
            <form method="POST" action="{{ route('pin.verify') }}" class="mt-4">
                @csrf
                <input type="password" name="pin" class="form-control form-control-lg text-center mb-3" maxlength="4" pattern="\d{4}" placeholder="****" required autofocus>
                <button type="submit" class="btn btn-shky w-100">Valider</button>
            </form>
        </div>
    </div>
</div>
@endsection
