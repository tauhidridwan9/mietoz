@extends('layouts.owner.app')

@section('content')
    <div class="container">
        <h1>Laporan Omzet</h1>

        <!-- Date Filter -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="start-date">Start Date:</label>
                <input type="date" id="start-date" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="end-date">End Date:</label>
                <input type="date" id="end-date" class="form-control">
            </div>
        </div>

        <div class="table-responsive">
            <table id="reportTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Pembeli</th>
                        <th>Produk</th>
                        <th>Tanggal</th>
                        <th>Kuantitas Yang di Beli</th>
                        <th>Omzet Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportItems as $item)
                        <tr data-date="{{ $item->created_at->format('Y-m-d') }}">
                            <td>{{ $item->buyer_name }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->created_at->format('Y-m-d') }}</td>
                            <td>{{ $item->total_quantity }} unit</td>
                            <td>Rp {{ number_format($item->total_revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tampilkan Total Omzet -->
        <div class="mt-3">
            <h4>Total Omzet: <span id="totalOmzet">Rp 0.00</span></h4>
        </div>
    </div>
@endsection