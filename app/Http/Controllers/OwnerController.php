<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $salesData = OrderItem::select(DB::raw('DATE(order_items.created_at) as date'), DB::raw('SUM(order_items.quantity) as total_sales'))
        ->join('products', 'order_items.product_id', '=', 'products.id') // Menghubungkan tabel
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        $dates = $salesData->pluck('date');
        $sales = $salesData->pluck('total_sales');
        $totalIncome = Order::where('status', 'completed')->sum('total_amount'); // Asumsi total_amount ada di tabel orders
        $totalAdmins = User::where('role_id', 2)->count();
        $totalUsers = User::where('role_id', 1)->count();

        $users = User::where('role_id', 1)->get(); // Ambil semua user
        $admins = User::where('role_id', 2)->get(); // Ambil semua admin

        Log::info('Dates: ', $dates->toArray());
        Log::info('Sales: ', $sales->toArray());
        $monthlyRevenue = OrderItem::select(DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m") as month'), DB::raw('SUM(quantity * price) as total_revenue'))
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', 'completed')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Siapkan data untuk chart
        $months = $monthlyRevenue->pluck('month');
        $revenues = $monthlyRevenue->pluck('total_revenue');


        return view('owner.dashboard', compact('totalIncome', 'totalAdmins', 'totalUsers', 'users', 'admins','dates', 'sales','months', 'revenues'));
    }

    public function reports(Request $request)
    {
        // Ambil data laporan pendapatan dengan orderItems, relasi product, dan nama user
        $reportItems = OrderItem::select(
            'order_items.product_name',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
            'orders.created_at',  // Include the order date
            'users.name as buyer_name' // Select buyer name
        )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id') // Join with users table
            ->where('orders.status', 'completed')  // Only include completed orders
            ->groupBy('order_items.product_name', 'orders.created_at', 'users.name')  // Group by date and buyer name as well
            ->get();

        return view('owner.reports.index', compact('reportItems'));
    }





}
