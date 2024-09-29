@extends('layouts.customer.app')

@section('content')
<div class="container">
    <h1>Order Sukses</h1>
    <p>Pesanan Anda dengan ID {{ $order->id }} telah berhasil diproses.</p>

    @if($order->orderItems && $order->orderItems->isNotEmpty())
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
                <th>
                Status
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->quantity * $item->price }}</td>
                <td>{{ $item->order->status }}</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
    @else
    <p>Detail pesanan tidak tersedia.</p>
    @endif
</div>
@endsection