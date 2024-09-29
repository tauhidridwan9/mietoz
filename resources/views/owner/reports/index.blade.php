@extends('layouts.owner.app')

@section('content')
    <div class="container">
        <h1>Laporan Pendapatan</h1>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Banyaknya Produk Terjual</th>
                        <th>Pendapatan Produk</th>
                        <th>Stok Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportItems as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->total_quantity }} unit</td>
                            <td>Rp {{ number_format($item->total_revenue, 2) }}</td>
                            <td>
                                @foreach($productStocks as $product)
                                    @if($product->name == $item->product_name)
                                        {{ $product->stock }}
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
