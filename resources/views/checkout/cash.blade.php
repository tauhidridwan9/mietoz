@extends('layouts.customer.app')

@section('content')
<div class="container">
    <h1>Pembayaran Cash</h1>

    <p>Tunjukan struk pembayaran ke kasir.</p>

    <table class="table">
        <thead>
            <tr>
                <th>Nomor Pesanan</th>
                <th>Tanggal Pesanan</th>
                <th>Nama</th>
                <th>Menu Pesanan</th>
                <th>Kuantitas</th>
                <th>Total Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @if($order->orderItems->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">Keranjang kosong</td>
                </tr>
            @else
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp. {{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('cash.payment.pdf', $order->id) }}'">Download Struk</button>
</div>
@endsection
