@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h1>Kelola Pesanan</h1>
    @if($orders->isNotEmpty())
    @foreach($orders as $order)
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Pesanan dari: {{ $order->user->name }}</h5> <!-- Menampilkan nama user -->
        </div>
        <div class="card-body">
            <h5 class="card-title">Rincian Pesanan #{{ $order->id }}</h5>
            <ul>
                @foreach($order->orderItems as $item)
                <li>
                    <strong>Produk:</strong> {{ $item->product_name }} <br>
                    <strong>Quantity:</strong> {{ $item->quantity }} <br>
                </li>
                @endforeach
            </ul>

            <p><strong>Alamat:</strong> {{ $order->user->alamat }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>

            @if($order->status == 'paid' || $order->status == 'cash')
            <form action="{{ route('orders.accept', $order->id) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-primary">Terima Pesanan</button>
            </form>
            @endif

            @if($order->status == 'processing')
            <form action="{{ route('orders.delivered', $order->id) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-success">Kirim Pesanan</button>
            </form>
            @endif

            <!-- Button to view PDF -->
            @if($order->pdf_link)
            <a href="{{ route('orders.pdf', $order->id) }}" class="mt-2 btn btn-secondary">Lihat PDF</a>

            @endif

        </div>
    </div>
    @endforeach
    @else
    <p>Detail pesanan tidak tersedia.</p>
    @endif
</div>
@endsection