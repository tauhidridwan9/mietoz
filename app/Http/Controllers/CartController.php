<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
    // Fungsi untuk mendapatkan jumlah item di keranjang
    public function getCartCount()
    {
        if (!auth()->check()) {
            return 0;
        }

        // Ambil semua item keranjang berdasarkan user yang login
        $cartItems = CartItem::whereHas('cart', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        // Pastikan cartItems adalah koleksi
        return $cartItems->sum('quantity'); // Menghitung total quantity dari semua item di keranjang
    }


    // Fungsi untuk menampilkan halaman keranjang
    public function cart()
    {
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirect ke halaman login jika belum login
        }

        // Dapatkan user ID dari user yang sedang login
        $userId = auth()->id();

        // Ambil keranjang user yang login
        $cart = Cart::where('user_id', $userId)->first();

        // Jika keranjang ditemukan, ambil item dari tabel cart_items, jika tidak, buat collection kosong
        $items = collect(); // Inisialisasi collection kosong
        if ($cart) {
            $items = CartItem::where('cart_id', $cart->id)->get();
        }

        return view('cart.index', compact('items')); // Tampilkan halaman keranjang dengan item yang ada
    }



    public function add(Request $request, $productId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $product = Product::findOrFail($productId);

        Log::info('Adding product to cart', ['product_id' => $product->id, 'user_id' => auth()->id()]);

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = CartItem::firstOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id
            ],
            [
                'quantity' => $request->input('quantity', 1),
                'price' => $product->price,
                'name' => $product->name,
                'image' => $product->image
            ]
        );

        // Tambahkan jumlah jika produk sudah ada di keranjang
        if ($cartItem->wasRecentlyCreated) {
            // Produk baru ditambahkan
        } else {
            $cartItem->increment('quantity', $request->input('quantity', 1));
        }

        return redirect()->route('home')->with('addtocart', 'Produk berhasil ditambahkan ke keranjang!');
    }


    public function update(Request $request, $cartItemId)
    {
        // Pastikan pengguna login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Validasi input
        $request->validate(['quantity' => 'required|integer|min:1']);

        // Dapatkan keranjang pengguna yang login
        $cart = Cart::where('user_id', auth()->id())->firstOrFail();

        // Dapatkan item keranjang berdasarkan ID
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $cartItemId)
            ->firstOrFail();

        // Dapatkan produk terkait
        $product = $cartItem->product;

        // Cek stock produk
        if ($request->input('quantity') > $product->stock) {
            return redirect()->route('cart.index')->with('error', 'Stock produk habis!');
        }

        // Update jumlah item
        $cartItem->update(['quantity' => $request->input('quantity')]);

        return redirect()->route('cart.index')->with('success', 'Jumlah produk berhasil diperbarui!');
    }

    public function remove($cartItemId)
    {
        // Pastikan pengguna login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Dapatkan keranjang pengguna yang login
        $cart = Cart::where('user_id', auth()->id())->firstOrFail();

        // Dapatkan item keranjang berdasarkan ID
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $cartItemId)
            ->firstOrFail();

        // Hapus item dari keranjang
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

}
