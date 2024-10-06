<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class CheckoutController extends Controller
{
    public function index()
    {
        // Ambil data cart dari database
        $cartItems = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();// Ambil item dari keranjang

        Log::info('Data Cart from database:', ['cart' => $cartItems]);

        // Hitung total dari item di keranjang
        $totalAmount = $this->calculateTotal($cartItems);

        // Buat instance order baru
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $totalAmount,
        ]);
        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name, // Or use $cartItem->name if it exists
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price
            ]);
        }

        // Kirim data keranjang dan order ke view
        return view('checkout.index', [
            'cart' => $cartItems,
            'order' => $order,
            'totalAmount' => $totalAmount, // Kirim total amount ke view
        ]);
    }

    private function calculateTotal($cartItems)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            // Mengakses properti price dan quantity
            $total += $item->price * $item->quantity;
        }
        return $total;
    }
    public function cash($order_id)
    {
        $order = Order::with('user', 'orderItems')->find($order_id);
        $order->update(['status' => 'cash']);
        $cart = Cart::where('user_id', auth()->id())->first();

        // Ambil item keranjang terkait user yang login
        $cartItems = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();
       
        $pdf = PDF::loadView('orders.resi', compact('order'));

        $pdfFileName = 'pdf/resi_' . $order->id . '.pdf'; // Relative path for 'public' disk
        $pdf->save(storage_path('app/public/' . $pdfFileName)); // Save PDF to storage/public/pdf/

        
        // Store the relative PDF path in the order record
        $order->resi = $pdfFileName;
        $order->save();

        $admin = User::where('role_id', 2)->first(); // Fetch admin
        $admin->notify(new NewOrderNotification($order));
        if ($cartItems) {
            $cartItems->each->delete();
            // Hapus cart
            $cart->delete();
        }



        return view('checkout.cash', compact('order', 'cartItems'))->with('success', 'Pesanan Anda berhasil diproses.');

    }

    public function confirm($order_id)
    {
        $order = Order::find($order_id);

        // Proses konfirmasi pembayaran cash
        // ...

        return redirect()->route('order.success', $order->id);
    }
    public function pdf($order_id)
    {
        // Ambil data pesanan beserta relasi user dan orderItems
        $order = Order::with('user', 'orderItems')->findOrFail($order_id);

        // Buat PDF dari tampilan 'orders.resi'
        $pdf = PDF::loadView('orders.resi', compact('order'));

        // Tampilkan PDF tanpa menyimpannya
        return $pdf->stream('resi_' . $order->id . '.pdf');
    }

}
