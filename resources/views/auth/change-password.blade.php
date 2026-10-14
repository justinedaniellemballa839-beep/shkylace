@extends('layouts.app')
@section('title', 'Mot de passe')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="auth-card form-shky">
                <h4 class="fw-bold mb-4">Modifier le mot de passe</h4>
                <form method="POST" action="{{ route('password.change') }}">
                    @csrf
                    <div class="mb-3">
                        <label>Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Confirmer</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-shky">Enregistrer</button>
                </form>
                <hr class="my-4">
                <h5>Configurer le PIN USSD/SMS</h5>
                <form method="POST" action="{{ route('pin.setup') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="password" name="pin" class="form-control" maxlength="4" placeholder="Nouveau PIN" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="pin_confirmation" class="form-control" maxlength="4" placeholder="Confirmer PIN" required>
                    </div>
                    <button type="submit" class="btn btn-shky-outline">Configurer PIN</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
