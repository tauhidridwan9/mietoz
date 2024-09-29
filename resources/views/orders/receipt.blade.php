<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <style>
        /* General body styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }

        .receipt-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            color:  #e74c3c;;
        }

        .details {
            margin-bottom: 30px;
        }

        .details p {
            margin: 0;
            padding: 5px 0;
            font-size: 14px;
        }

        .details span {
            font-weight: bold;
        }

        .details .float-right {
            float: right;
        }

        /* Table styling for order items */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color:  #e74c3c;;
            color: #fff;
            font-size: 14px;
        }

        td {
            font-size: 14px;
            color: #555;
        }

        /* Total section styling */
        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
        }

        .total span {
            color: #28a745;
            font-size: 20px;
        }

        /* Barcode section styling */
        .barcode {
            text-align: center;
            margin-top: 20px;
        }

        .barcode img {
            margin-bottom: 10px;
        }

        /* Footer section */
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .non-refundable {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #e74c3c;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <!-- Header Section -->
        <div class="header">
            <h1>MIETOZ</h1>
        </div>

        <!-- Order Details -->
        <div class="details">
            <p><span>NAMA</span> <span class="float-right">: {{ $order->user->name }}</span></p>
            <p><span>ALAMAT</span> <span class="float-right">: {{ $order->user->address }}</span></p>
        </div>

        <!-- Order Items -->
        <table>
            <thead>
                <tr>
                    <th>NAMA PRODUK</th>
                    <th>KUANTITAS</th>
                    <th>HARGA PER PIECE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->OrderItems as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp. {{ number_format($item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Amount -->
        <div class="total">
            TOTAL: <span>Rp. {{ number_format($order->orderItems->sum(fn($item) => $item->quantity * $item->price), 2) }}</span>
        </div>

        <!-- Barcode Section -->
        <div class="barcode">
            <img src="{{ $order->barcode_url }}" alt="Barcode" width="200" height="50">
            <p>Order ID: {{ $order->id }}</p>
        </div>

        <!-- Non-refundable Notice -->
        <div class="non-refundable">
            100% NON-REFUNDABLE
        </div>

        <!-- Footer Section -->
        <div class="footer">
            Thank you for your purchase! <br> For more information, visit www.mietoz.com
        </div>
    </div>
</body>

</html>
