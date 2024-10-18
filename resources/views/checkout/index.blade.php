@extends('layouts.customer.app')

@section('content')
<div class="container">
    <h1>Checkout</h1>
 @if($cart->isEmpty())
        <div class="alert alert-warning text-center">
            Keranjang kosong
        </div>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart as $details)
            <tr>
                <td>{{ $details['name'] }}</td>
                <td>{{ $details['quantity'] }}</td>
                <td>Rp {{ number_format($details['price'], 2, ',', '.') }}</td>
                <td>Rp {{ number_format($details['price'] * $details['quantity'], 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="container d-flex align-items-center justify-content-center p-2">
        <button id="pay-button" class="btn btn-primary">Pay</button>
        {{-- <a class="btn btn-warning text-decoration-none rounded-2 text-light btn-primary p-2" href="{{ route('cash.payment', $order->id) }}">Bayar Tunai</a> --}}
    </div>
    @endif

    </div>

    @endsection

    @section('scripts')
    <!-- Include Midtrans Snap.js -->
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function() {fetch('{{ route('payment.pay') }}', {method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_id: '{{ $order->id }}',
                            cart: @json($cart) // Pass data cart ke JavaScript
                        })
                    })
                .then(response => response.json())
                .then(data => {
                    if (data.snapToken) {
                        snap.pay(data.snapToken, {
                            onSuccess: function(result) {
                                window.location.href = '{{ route("payment.finish") }}?order_id={{ $order->id }}';
                            },
                            onPending: function(result) {
                                window.location.href = '{{ route("orders.pending", $order->id) }}';
                            },
                            onError: function(result) {
                                window.location.href = '{{ route("orders.failed", $order->id) }}';
                            }
                        });
                    } else {
                        alert('Gagal memproses pembayaran. Silakan coba lagi.');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    @endsection