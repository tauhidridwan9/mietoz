<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()

    {

        $users = User::whereHas('role', function ($query) {

            $query->where('name', 'customer');
        })->with(['orders', 'chats', 'sessions']) // Eager loading relasi orders, chats, dan sessions

            ->get();


        foreach ($users as $user) {

            $user->total_spending = $user->orders->sum('total_amount');

            $user->total_chat_count = $user->chats->count();

            $user->average_login_time = $user->average_login_time; // Menggunakan accessor untuk rata-rata waktu login

        }


        return view('owner.users.index', compact('users'));
    }
}