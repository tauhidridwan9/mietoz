
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
            @else
            <img src="https://via.placeholder.com/500" class="img-fluid" alt="{{ $product->name }}">
            @endif
        </div>
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p>{{ $product->description }}</p>
            <p><strong>Harga:</strong> Rp {{ number_format($product->price, 2, ',', '.') }}</p>
            <p><strong>Stok:</strong> {{ $product->stock }} {{ $product->stock > 1 ? 'items' : 'item' }}</p>

            @auth
            @if($product->stock > 0)
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Add to Cart <i class="fas fa-shopping-cart"></i></button>
            </form>
            @else
            <button class="btn btn-secondary" disabled>Out of Stock</button>
            @endif
            @else
            <a href="{{ route('login') }}" class="btn btn-primary" id="login-button">Add to Cart</a>
            @endauth
        </div>
    </div>
</div>
@endsection
