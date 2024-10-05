@extends('layouts.admin.app')

@section('content')
 
<div class="container">
    <h1>Menu Kasir</h1>
     <form action="{{ route('cash.payment.process', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
    

        <div class="form-group mb-3">
            <label for="name">Nomor Order</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $order->id) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="tagihan">Tagihan</label>
            <input name="tagihan" id="tagihan" class="form-control" rows="4" required value="{{old('tagihan', $order->total_amount)}}" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="nominal">Nominal Uang</label>
            <input name="nominal" id="nominal" class="form-control" rows="4" required></input>
        </div>
        @if (session('success'))
          <div class="form-group mb-3">
            <label for="kembalian">Kembalian</label>
            <input name="kembalian" id="kembalian" class="form-control" rows="4" value="{{ $kembalian }}" readonly>
        </div>
         @endif


        <div class="form-group mb-3">
    @if (!session('success'))
        <button type="submit" class="btn btn-primary">Input</button>
    @endif
    @if (session('success'))
        <a href="{{ route('orders.manage.process') }}" class="btn btn-secondary">Kembali</a>
    @endif
</div>
         
    </form>
</div>
@endsection