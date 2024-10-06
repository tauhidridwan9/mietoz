<!DOCTYPE html>
<html>

<head>
    <title>Order Completed</title>
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }

        ul {
            padding: 0;
            list-style-type: none;
        }

        ul li {
            background-color: #ecf0f1;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        ul li span {
            font-weight: bold;
            color: #2c3e50;
        }

        .thank-you {
            text-align: center;
            color: #27ae60;
            font-size: 18px;
            margin-top: 30px;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container a {
            background-color: #2980b9;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            font-size: 16px;
        }

        .button-container a:hover {
            background-color: #3498db;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #95a5a6;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <h1>Order Completed</h1>

        <p>Your order has been successfully completed. Here are the details:</p>

        <ul>
            @foreach($orderItems as $item)
                <li><span>Product:</span> {{ $item->product_name }}</li>
            @endforeach
        </ul>

        <div class="thank-you">
            <p>Thank you for shopping with us!</p>
        </div>

        <div class="button-container">
            <a href="{{ route('products.index') }}">Continue Shopping</a>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
