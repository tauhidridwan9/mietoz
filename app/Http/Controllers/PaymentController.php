<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Midtrans\Snap;
use App\Mail\OrderCompletedNotification;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Notifications\StockDepletedNotification;
use Illuminate\Support\Facades\DB;








class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }
    public function cashPayment(Request $request)

    {

        // Get order ID from request

        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);

        $order->status = 'paid';
        $order->save();
    
        // Return response

        return response()->json([

            'status' => 'success',

            'message' => 'Pembayaran cash berhasil',

        ]);
    }

    public function pay(Request $request)
    {
        // Validasi input
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        // Temukan order berdasarkan ID
        $order = Order::findOrFail($request->input('order_id'));

        // Konversi total_amount ke float
        $grossAmount = (float) $order->total_amount;

        // Log data untuk debugging
        Log::info('Midtrans Snap Request Data:', [
            'order_id' => $order->id,
            'gross_amount' => $grossAmount,
        ]);

        // Setup Midtrans Snap
        try {
            $snapToken = \Midtrans\Snap::createTransaction([
                'transaction_details' => [
                    'order_id' => $order->id,
                    'gross_amount' => $grossAmount,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
            ]);


            return response()->json(['snapToken' => $snapToken->token]);
        } catch (\Exception $e) {
            // Tampilkan error untuk debugging
            dd($e->getMessage());
        }
    }

    public function finish(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);

        $order->status = 'paid';
        $order->save();

        // Ambil keranjang berdasarkan user yang login
        $cart = Cart::where('user_id', auth()->id())->first();

        // Ambil item keranjang terkait user yang login
        $cartItems = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        if ($cartItems->isNotEmpty()) {
            // Loop melalui setiap item keranjang dan buat item pesanan
            foreach ($cartItems as $cartItem) {
                // Dapatkan produk berdasarkan ID dari CartItem
                $product = Product::find($cartItem->product_id);

                if ($product) {
                    // Buat item order berdasarkan item keranjang
                    // OrderItem::create([
                    //     'order_id' => $order->id,
                    //     'product_id' => $product->id,
                    //     'product_name' => $product->name, // Atau gunakan $cartItem['name'] jika ada dalam item
                    //     'quantity' => $cartItem->quantity,
                    //     'price' => $cartItem->price
                    // ]);

                    // Kurangi stok produk sesuai dengan jumlah yang diorder
                    $product->decrement('stock', $cartItem->quantity);

                    // Jika stok habis, beri notifikasi kepada admin
                    if ($product->stock <= 0) {
                        $admin = User::where('role_id', 3)->first();
                        $admin->notify(new StockDepletedNotification($product));
                    }

                    // Notify admin about the new order
                    $admin = User::where('role_id', 2)->first(); // Fetch admin
                    $admin->notify(new NewOrderNotification($order));
                }
            }

            // Hapus item keranjang setelah order diproses
            $cartItems->each->delete();
            // Hapus cart
            $cart->delete();
        }

        return redirect()->route('order.success');
    }


    public function handleCallback(Request $request)
    {
        $data = $request->all();

        // Verifikasi signature key
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $gross_amount = $data['gross_amount'];
        $gross_amount = str_replace(".00", "", $gross_amount);

        $hash_string = $data['order_id'] . $data['status_code'] . $gross_amount . $serverKey;
        $hashedKey = hash('sha512', $hash_string);

        if ($hashedKey !== $data['signature_key']) {
            return response()->json(['message' => 'Invalid signature key', 'data' => $data['signature_key'], 'signature_key' => $hashedKey, 'server_key' => $serverKey, 'gross_amount' => $data['gross_amount'], 'status_code' => $data['status_code'], 'hash_string' => $hash_string], 403);
        }

        // Dapatkan status transaksi dan order ID
        $transactionStatus = $data['transaction_status'];
        $orderId = $data['order_id'];
        $transactionId = $data['transaction_id'] ?? null; // Dapatkan transaction_id dari respons

        $order = Order::where('id', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Simpan transaction_id ke dalam kolom transaction_id di tabel orders
        if ($transactionId) {
            $order->transaction_id = $transactionId;
        }

        // Update status pesanan berdasarkan status transaksi
        switch ($transactionStatus) {
            case 'capture':
                if ($data['payment_type'] == 'credit_card') {
                    if ($data['fraud_status'] == 'challenge') {
                        $order->status = 'pending';
                    } else {
                        $order->status = 'paid';
                    }
                }
                break;

            case 'settlement':
                $order->status = 'paid';
                break;

            case 'pending':
                $order->status = 'pending';
                break;

            case 'deny':
                $order->status = 'failed';
                break;

            case 'expire':
                $order->status = 'expired';
                break;

            case 'cancel':
                $order->status = 'canceled';
                break;

            default:
                $order->status = 'unknown';
                break;
        }

        // Simpan perubahan ke database
        $order->save();

        return response()->json(['status' => 'success']);
    }


}
