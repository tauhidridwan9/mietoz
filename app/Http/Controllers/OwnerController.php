<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $totalIncome = Order::where('status', 'delivered')->sum('total_amount'); // Asumsi total_amount ada di tabel orders
        $totalAdmins = User::where('role_id', 2)->count();
        $totalUsers = User::where('role_id', 1)->count();

        $users = User::where('role_id', 1)->get(); // Ambil semua user
        $admins = User::where('role_id', 2)->get(); // Ambil semua admin

        return view('owner.dashboard', compact('totalIncome', 'totalAdmins', 'totalUsers', 'users', 'admins'));
    }

    public function reports()
    {
        // Ambil data laporan pendapatan dengan orderItems dan relasi product
        $reportItems = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(quantity * price) as total_revenue'))
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', 'delivered')  // Hanya hitung order dengan status 'delivered'
        ->groupBy('product_name')
        ->get();


        // Ambil data stok produk
        $productStocks = Product::all();

        return view('owner.reports.index', compact('reportItems', 'productStocks'));
    }


}
