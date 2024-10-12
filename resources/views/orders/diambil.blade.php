@extends('layouts.admin.app')

@section('content')
@if (session('error'))
 @section('scripts')
    <script>
        Swal.fire({
            title: 'Error',
            text: '{{ session('error') }}',
            icon: 'error',
            showCancelButton: false,
            confirmButtonText: 'OK'
        });
    </script>
    @endsection
    @endif

    

     
<div class="container">
    <h1>Kelola Pesanan</h1>
     <form action="{{ route('orders.manage.cooking') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari pesanan berdasarkan nama atau ID..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </div>
    </form>
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

            @if($order->status == 'cash')
            <form action="{{ route('orders.accept', $order->id) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-primary">Terima Pesanan</button>
            </form>
            @endif

            @if($order->status == 'delivered')
            <form action="{{ route('orders.selesai', $order->id) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-success">Selesaikan</button>
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
