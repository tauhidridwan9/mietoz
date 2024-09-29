<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Cash</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pembayaran Cash</h1>

        <p>Silakan melakukan pembayaran cash ke kasir.</p>

        <table class="table">
            <thead>
                <tr>
                    <th>Nomor Pesanan</th>
                    <th>Tanggal Pesanan</th>
                    <th>Nama</th>
                    <th>Menu Pesanan</th>
                    <th>Kuantitas</th>
                    <th>Total Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp. {{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p>Silakan tunjukkan PDF ini ke kasir untuk menukarkannya dengan barang pesanan.</p>
    </div>
</body>
</html>