<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancelled Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Order Cancelled</h1>
    </div>
    <div class="content">
        <p>Hai {{ $order->user->name }},</p>

        <p>Kami informasikan kepada anda bahwa order ID <strong>{{ $order->id }}</strong> dibatalkan.</p>

        <p>Hal itu dikarenakan stok barang yang kamu pesan habis:</p>
        <ul>
            <li>{{ $productName }} (Kuantitas: {{ $quantity }})</li>
        </ul>

        <p>Total:  <strong>Rp. {{ number_format($totalAmount, 2) }}</strong>.</p>

        <p>Kami memohon maaf atas ketidaknyamanannya.</p>

        <a href="{{ route('order.success') }}" class="button">View Your Orders</a>
    </div>
    <div class="footer">
        <p>Thank you for choosing our service!</p>
        <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
    </div>
</div>

</body>
</html>
