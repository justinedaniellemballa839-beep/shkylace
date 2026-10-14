@extends('layouts.app')
@section('title', 'Connexion')
@section('content')
<div class="auth-wrapper">
    <div class="container">
        <div class="auth-card form-shky">
            <div class="auth-logo">
                <h2><i class="bi bi-shop-window"></i> Shkyplace</h2>
                <p class="text-muted">Connectez-vous à votre compte</p>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label>Adresse email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>
                <button type="submit" class="btn btn-shky w-100">Se connecter</button>
            </form>
            <p class="text-center mt-3 mb-0">Pas de compte ? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color: var(--sp-primary)">S'inscrire</a></p>
        </div>
    </div>
</div>
@endsection
