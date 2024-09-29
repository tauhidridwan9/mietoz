@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Buat Pesanan</h1>

    <form id="orderForm">
        @csrf
        <div class="form-group">
            <label for="product_id">Pilih Produk</label>
            <select class="form-control" id="product_id" name="product_id" required>
                @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} - Rp {{ number_format($product->price, 2, ',', '.') }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Jumlah</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Pesan</button>
    </form>
</div>

<script>
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Implement your Midtrans payment logic here
    });
</script>
@endsection