@extends('layouts.admin.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard Kasir</h1>

    <div class="row mb-3">
        <div class="col-12">
            @if($notifications->isNotEmpty())
            <div class="alert alert-info">
                <h4>Notifications</h4>
                <form action="{{ route('admin.notifications.clearAll') }}" method="POST" class="mb-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Clear All</button>
                </form>
                @foreach($notifications as $notification)
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <div class="col-10">{{ $notification->data['message'] }}</div>
                    <div class="col-1 m-sm-2">
                        @if(!$notification->read_at)
                        <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">Mark as Read</button>
                        </form>
                        @endif

                        @if(isset($notification->data['product_id']))
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">View Product</a>
                        @endif
                    </div>
                    <div class="col-1">
                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p>No notifications at this time.</p>
            @endif
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4 mb-3">
            <div class="card shadow h-100 d-flex flex-column">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title">Jumlah Produk</h5>
                </div>
                <div class="card-body text-center flex-grow-1">
                    <p class="card-text fs-2">{{ $productCount }} produk</p>
                    <i class="fas fa-box fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow h-100 d-flex flex-column">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title">Pesanan Online</h5>
                </div>
                <div class="card-body text-center flex-grow-1">
                    <p class="card- fs-2 order-count">{{ $orderCount }} pesanan</p>
                    <i class="fas fa-shopping-cart fa-2x"></i>
                    <a href="{{ route('orders.manage') }}" class="btn btn-primary mt-2">Kelola Pesanan</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow h-100 d-flex flex-column">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title">Pesanan Offline</h5>
                </div>
                <div class="card-body text-center flex-grow-1">
                    <p class="card- fs-2 count-processing">{{ $countProcessing }} pesanan</p>
                    <i class="fas fa-shopping-cart fa-2x"></i>
                    <a href="{{ route('orders.manage.process') }}" class="btn btn-primary mt-2">Kelola Pesanan</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow h-100 d-flex flex-column">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title">Sedang dimasak</h5>
                </div>
                <div class="card-body text-center flex-grow-1">
                    <p class="card- fs-2 count-cooking">{{ $countCooking }} pesanan sedang dimasak</p>
                    <i class="fas fa-shopping-cart fa-2x"></i>
                    <a href="{{ route('orders.manage.cooking') }}" class="btn btn-primary mt-2">Kelola Pesanan</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow h-100 d-flex flex-column">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title">Siap Diambil</h5>
                </div>
                <div class="card-body text-center flex-grow-1">
                    <p class="card- fs-2 count-diambil">{{ $countDiambil }} Siap Diambil</p>
                    <i class="fas fa-shopping-cart fa-2x"></i>
                    <a href="{{ route('orders.manage.diambil') }}" class="btn btn-primary mt-2">Kelola Pesanan</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow h-100 d-flex flex-column">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title">Jumlah Customer</h5>
                </div>
                <div class="card-body text-center flex-grow-1">
                    <p class="card-text fs-2 customer-count">{{ $customerCount }} customer</p>
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')

@endsection



