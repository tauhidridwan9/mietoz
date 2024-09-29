@extends('layouts.customer.app')

@section('content')
<div class="container">
    <h1>Daftar Pesanan</h1>

    <div class="list-group">
        @foreach ($orders->sortByDesc('created_at') as $order)
        <a href="{{ route('orders.show', $order->id) }}" class="list-group-item list-group-item-action">
            <h5 class="mb-1">Pesanan #{{ $order->id }}</h5>
            <p class="mb-1">Total: Rp {{ number_format($order->total_amount, 2, ',', '.') }}</p>
            <small>Status: {{ ucfirst($order->status) }}</small>
        </a>
        @endforeach
    </div>
</div>
@endsection