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
        <p>Dear {{ $order->user->name }},</p>

        <p>We regret to inform you that your order with ID <strong>{{ $order->id }}</strong> has been cancelled.</p>

        <p>The following item(s) are out of stock:</p>
        <ul>
            <li>{{ $productName }} (Quantity: {{ $quantity }})</li>
        </ul>

        <p>The total amount for this order was <strong>Rp. {{ number_format($totalAmount, 2) }}</strong>.</p>

        <p>We apologize for any inconvenience this may cause. Please feel free to reach out if you have any questions or would like assistance with another order.</p>

        <a href="{{ route('order.success') }}" class="button">View Your Orders</a>
    </div>
    <div class="footer">
        <p>Thank you for choosing our service!</p>
        <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
    </div>
</div>

</body>
</html>
