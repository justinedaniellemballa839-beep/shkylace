@extends('layouts.app')
@section('title', 'Inscription')
@section('content')
<div class="auth-wrapper">
    <div class="container">
        <div class="auth-card form-shky" style="max-width: 560px;">
            <div class="auth-logo">
                <h2>Créer un compte</h2>
                <p class="text-muted">Rejoignez la communauté Shkyplace</p>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Prénom</label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Nom</label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label>Téléphone</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="6XX XXX XXX" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Ville</label>
                        <input type="text" name="ville" class="form-control" value="{{ old('ville') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Quartier</label>
                        <input type="text" name="quartier" class="form-control" value="{{ old('quartier') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label>Type de compte</label>
                    <select name="role" class="form-select" required>
                        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="vendeur" {{ old('role') == 'vendeur' ? 'selected' : '' }}>Vendeur</option>
                        <option value="livreur" {{ old('role') == 'livreur' ? 'selected' : '' }}>Livreur</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Mot de passe</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Code PIN (4 chiffres, pour USSD/SMS)</label>
                    <input type="password" name="pin" class="form-control" maxlength="4" pattern="\d{4}" placeholder="****">
                    <small class="text-muted">Requis pour valider commandes et opérations sensibles sans internet</small>
                </div>
                <button type="submit" class="btn btn-shky w-100">Créer mon compte</button>
            </form>
            <p class="text-center mt-3 mb-0">Déjà inscrit ? <a href="{{ route('login') }}" style="color: var(--sp-primary)">Se connecter</a></p>
        </div>
    </div>
</div>
@endsection
