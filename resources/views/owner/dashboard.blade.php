@extends('layouts.owner.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard Owner</h1>

    <div class="row mb-4">
        <!-- Total Income -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Income</h5>
                    <p class="card-text display-6">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total Cashiers -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Cashiers</h5>
                    <p class="card-text display-6">{{ $totalAdmins }}</p>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Customers</h5>
                    <p class="card-text display-6">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Kelola Banner -->
        <div class="col-md-6 mb-3">
    <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">Kelola Banner</h5>
        </div>
        <div class="card-body d-flex align-items-center justify-content-center">
            <a href="{{ route('banner.index') }}" class="btn btn-primary">Kelola Banner</a>
        </div>
    </div>
</div>


        <!-- Kelola Kategori -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Kategori</h5>
                </div>
                <div class="card-body text-center">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">
                        <i class="fas fa-tags"></i> Kelola Kategori
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Kelola Produk -->
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Kelola Produk</h5>
                </div>
                <div class="card-body text-center">
                    <a href="{{ route('products.index') }}" class="btn btn-primary"><i class="fas fa-eye"></i>Lihat Produk</a>
                    <a href="{{ route('products.create') }}" class="btn btn-success"><i class="fas fa-plus"></i>Tambah Produk</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Customer Behavior -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Customer Behavior</h5>
                    <a href="{{ route('owner.users.index') }}" class="btn btn-primary"><i class="fas fa-person"></i>Customer Behavior</a>
                </div>
            </div>
        </div>

        <!-- Manage Cashiers -->
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Cashiers</h5>
                    <a href="{{ route('owner.admins.index') }}" class="btn btn-primary"><i class="fa-solid fa-money-bill"></i>Manage Cashier</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Reports -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Reports</h5>
                    <a href="{{ route('owner.reports') }}" class="btn btn-success"><i class="fas fa-book"></i>View Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
