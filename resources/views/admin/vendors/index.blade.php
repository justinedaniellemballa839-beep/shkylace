@extends('layouts.app')
@section('title', 'Vendeurs')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Gestion des vendeurs</h2>
    <table class="table table-shky">
        <thead><tr><th>Boutique</th><th>Vendeur</th><th>Marché</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($shops as $shop)
                <tr>
                    <td>{{ $shop->name }}</td>
                    <td>{{ $shop->user->full_name }}</td>
                    <td>{{ $shop->market->name ?? '-' }}</td>
                    <td><span class="badge bg-{{ $shop->status === 'approved' ? 'success' : ($shop->status === 'pending' ? 'warning' : 'danger') }}">{{ $shop->status }}</span></td>
                    <td>
                        @if($shop->status !== 'approved')
                            <form action="{{ route('admin.vendors.approve', $shop) }}" method="POST" class="d-inline">@csrf @method('PUT')<button class="btn btn-sm btn-success">Approuver</button></form>
                        @endif
                        @if($shop->status !== 'suspended')
                            <form action="{{ route('admin.vendors.suspend', $shop) }}" method="POST" class="d-inline">@csrf @method('PUT')<button class="btn btn-sm btn-danger">Suspendre</button></form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $shops->links() }}
</div>
@endsection
