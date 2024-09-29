<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function dashboard()
    {
        $productCount = Product::count();
        $orderCount = Order::whereIn('status', ['paid', 'cash'])->count();
        $customerCount = User::where('role_id', 1)->count();
        $bannerCount = Banner::count();
        $categoryCount = Category::count();
        $notifications = auth()->user()->notifications;
        $countProcessing = Order::where('status', 'processing')->count();

        return view('admin.dashboard', compact('productCount', 'orderCount', 'customerCount', 'notifications', 'bannerCount', 'categoryCount', 'countProcessing'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);

        return redirect()->back()->with('success', 'Admin added successfully.');
    }

    public function markNotificationAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read');
    }

}

