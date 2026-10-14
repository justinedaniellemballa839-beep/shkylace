@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Gestion des utilisateurs</h2>
    <div class="table-responsive">
        <table class="table table-shky">
            <thead><tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Rôle</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                        <td>{{ $user->is_active ? 'Actif' : 'Suspendu' }}</td>
                        <td>
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">@csrf @method('PUT')
                                <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">{{ $user->is_active ? 'Suspendre' : 'Activer' }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
@endsection
