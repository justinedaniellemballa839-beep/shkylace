@extends('layouts.app')
@section('title', 'Mes commandes')
@section('content')
<div class="container py-5">
    <h2 class="section-title mb-4">Mes commandes</h2>
    <div class="table-responsive">
        <table class="table table-shky">
            <thead>
                <tr>
                    <th>N° Commande</th>
                    <th>Boutique</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="fw-semibold">{{ $order->order_number }}</td>
                        <td>{{ $order->shop->name }}</td>
                        <td>{{ number_format($order->total, 0, ',', ' ') }} F</td>
                        <td><span class="badge-status badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-shky-outline">Détails</a>
                            @if($order->status === 'en_attente')
                                <a href="{{ route('payments.show', $order) }}" class="btn btn-sm btn-shky">Payer</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Aucune commande</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</div>
@endsection
