@extends('layouts.owner.app')

@section('content')
<div class="container">
    <h1>Daftar Produk</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3"><i class="fa-solid fa-plus"></i>Tambah Produk</a>

    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-4 mb-3">
            <div class="product">
                @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" style=" width: 100%;
            height: 200px;
            /* fixed height for image */
            object-fit: cover;" class="thumbnail" alt="{{ $product->name }}">
                @endif
                <div class="body-product">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ $product->description }}</p>
                    <p>Stock: {{ $product->stock > 0 ? $product->stock : 'Out of stock' }}</p>
                    <p class="card-text">Rp {{ number_format($product->price, 2, ',', '.') }}</p>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning"><i class="fa-solid fa-edit"></i>Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i>Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection