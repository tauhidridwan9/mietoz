@extends('layouts.customer.app')

@section('content')
<div class="container">
    <h1>Detail Pesanan</h1>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">Pesanan ID: {{ $order->id }}</h5>
        </div>
        <div class="card-body">
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Tanggal Pesanan:</strong> {{ $order->created_at->format('d-m-Y H:i:s') }}</p>
            <p ><strong>Total: </strong> Rp {{ number_format($order->total_amount, 2, ',', '.') }}</p>
        </div>
    </div>

    @if($order->orderItems->isNotEmpty())
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->quantity * $item->price }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
     
    @endif
  
    <embed src="{{ !empty($order->pdf_link) ? $pdf_link : ( !empty($order->resi) ? $pdfPath : '' ) }}" type="application/pdf" width="100%" height="500px" />

@if (empty($pdf_link) && empty($pdfPath))
    <p>Tidak ada dokumen PDF yang tersedia.</p>
@endif



    <a href="{{ route('orders.index') }}" class="btn btn-primary">Kembali ke Daftar Pesanan</a>
</div>
@endsection