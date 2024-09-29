<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;


class ReportController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('status', 'completed')->sum('total_amount');
        $totalCustomers = User::where('role_id', Role::where('name', 'customer')->first()->id)->count();

        return view('reports.index', [
            'totalSales' => $totalSales,
            'totalCustomers' => $totalCustomers,
        ]);
    }
}

