<!-- resources/views/cart/index.blade.php -->
@extends('layouts.customer.app')

@section('content')
<div class="container">
    <h1>Keranjang Belanja</h1>
    @if (session('error'))
    @section('scripts')
    <script>

                        Swal.fire({
                            title: 'Stock Tidak Cukup',
                            text: 'Kuantitas anda melebihi stock yang kami punya.',
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonText: 'OK'
                        });
    </script>
    @endsection
    @endif

    @if($items->count())
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td>
                    @if ($item->product)
                    <img src="{{ asset('storage/' . $item->product->image) }}" width="50" alt="{{ $item->product->name }}">
                    <p>{{ $item->product->name }}</p>
                    @else
                    <p>Produk tidak ditemukan.</p>
                    @endif
                </td>
                <td>
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: flex; align-items: center;">
                        @csrf
                        @method('PUT')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control" style="width: 80px;">
                        <button type="submit" class="btn btn-success ml-2">Update</button>
                    </form>
                </td>
                <td>
                    Rp {{ number_format($item->price, 2, ',', '.') }}
                </td>
                <td>
                    Rp {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                </td>
                <td>
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        <a href="{{ route('checkout.index') }}" class="btn btn-success">Checkout</a>
    </div>
    @else
    <p>Keranjang Anda kosong.</p>
    @endif
    </div>
</div>
@endsection