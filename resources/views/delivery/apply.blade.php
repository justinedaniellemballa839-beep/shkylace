@extends('layouts.app')
@section('title', 'Devenir livreur')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-shky form-shky">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-2">Candidature livreur</h4>
                    <p class="text-muted mb-4">Commissions: 10% ponctualité + 5% satisfaction client</p>
                    <form method="POST" action="{{ route('delivery.apply.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3"><label>Prénom</label><input type="text" name="first_name" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Nom</label><input type="text" name="last_name" class="form-control" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Téléphone</label><input type="tel" name="phone" class="form-control" required></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label>Ville</label><input type="text" name="ville" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label>Quartier</label><input type="text" name="quartier" class="form-control" required></div>
                        </div>
                        <div class="mb-3"><label>Contact en cas de problème</label><input type="text" name="emergency_contact" class="form-control" required></div>
                        <div class="mb-3"><label>Lettre de motivation</label><textarea name="cover_letter" class="form-control" rows="3"></textarea></div>
                        <div class="mb-3"><label>CV (PDF)</label><input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx" required></div>
                        <div class="mb-3"><label>Photo</label><input type="file" name="photo" class="form-control" accept="image/*" required></div>
                        <div class="mb-3"><label>Photocopie CNI</label><input type="file" name="id_card" class="form-control" accept=".pdf,image/*" required></div>
                        <button type="submit" class="btn btn-shky">Envoyer ma candidature</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
