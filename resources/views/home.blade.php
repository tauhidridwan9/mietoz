@extends('layouts.customer.app')

@section('content')
<div class="container">
    @if (session('registsucces'))
    @section('scripts')
    <script>
        Swal.fire({
            title: 'Selamat Datang',
            text: 'Silahkan mulai pengalaman belanja yang menarik',
            icon: 'success',
            showCancelButton: false,
            confirmButtonText: 'OK'
        });
    </script>
    @endsection
    @endif
    <!-- Banner Section -->
    @php
    $banners = App\Models\Banner::latest()->take(5)->get();
    @endphp

    @if ($banners->count() > 0)
    <div id="bannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
            @foreach ($banners as $index => $banner)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $banner->path) }}" class="d-block w-100"
                    style="object-fit: cover; height: 300px; object-position: center top;" alt="Banner">
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    @endif

    <div class="mb-4">
        <form action="{{ route('products.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="price" class="form-control">
                    <option value="">Filter berdasarkan harga</option>
                    <option value="low_to_high" {{ request('price') == 'low_to_high' ? 'selected' : '' }}>Harga Rendah ke Tinggi</option>
                    <option value="high_to_low" {{ request('price') == 'high_to_low' ? 'selected' : '' }}>Harga Tinggi ke Rendah</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-control">
                    <option value="">Filter berdasarkan kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success w-100">Cari</button>
            </div>
        </form>
    </div>

    <h1>Produk Terbaru</h1>

    @if(isset($message))
    <div class="alert alert-info">
        {{ $message }}
    </div>
    @endif

    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-4 mb-3">
            <div class="card">
    <a href="{{ route('products.show', $product->id) }}" style="text-decoration: none; color: inherit;">
        @if ($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 200px; object-fit: cover;" class="card-img-top" alt="{{ $product->name }}">
        @endif
        <div class="card-body">
            <h5 class="fs-4 text-primary">{{ $product->name }}</h5>
            <p class="card-text text-secondary">{{ $product->description }}</p>
            <p class="card-text text-danger">Stok: {{ $product->stock }} {{ $product->stock > 1 ? 'items' : 'item' }}</p>
            <p class="card-text text-primary fs-4">Rp {{ number_format($product->price, 2, ',', '.') }}</p>
        </div>
    </a>
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
        @endforeach
    </div>



</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mendapatkan elemen tombol "Tambahkan ke Keranjang"
        var addToCartButtons = document.querySelectorAll('#login-button');

        addToCartButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah pengiriman formulir
                Swal.fire({
                    title: 'Anda Belum Login!',
                    text: 'Silakan login atau daftar untuk menambahkan produk ke keranjang.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Login',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            });
        });
    });
</script>
@endsection