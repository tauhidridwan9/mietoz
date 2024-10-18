@extends('layouts.owner.app')

@section('content')

<div class="container">
  
    <h1 class="mb-4">Dashboard Owner</h1>

    <div class="row mb-4">
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Penjualan Produk dari Waktu ke Waktu</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                     <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'bar', // Change to 'bar' to see if it renders
                data: {
                    labels: {!! json_encode($dates) !!},
                    datasets: [{
                        label: 'Total Produk Terjual',
                        data: {!! json_encode($sales) !!},
                        borderWidth: 1,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                    }]

                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
                </div>
            </div>
        </div>
    </div>


     <div class="row mb-4">
        <div class="col-md-12 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Omzet dari Waktu ke Waktu</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            var ctx = document.getElementById('revenueChart').getContext('2d');
                            var revenueChart = new Chart(ctx, {
                                type: 'line', // Use 'line' for revenue chart
                                data: {
                                    labels: {!! json_encode($months) !!}, // X-axis labels (months)
                                    datasets: [{
                                        label: 'Omzet (Rp)',
                                        data: {!! json_encode($revenues) !!}, // Y-axis data (revenue)
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderWidth: 2,
                                        fill: true // Fill area under the line
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                callback: function(value) {
                                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    

    <div class="row mb-4">
        <!-- Total Income -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Omzet</h5>
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
    @section('script')
<script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar', // Jenis grafik, misalnya 'line', 'bar', dll
        data: {
            labels: {!! json_encode($dates) !!}, // Label untuk sumbu X
            datasets: [{
                label: 'Total Penjualan',
                data: {!! json_encode($sales) !!}, // Data untuk grafik
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true // Mengisi area di bawah grafik
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
</div>
@endsection

