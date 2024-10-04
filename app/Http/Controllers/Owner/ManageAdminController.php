<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;



class ManageAdminController extends Controller
{
    public function index()
    {
        $admins = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->get();
        return view('owner.admins.index', compact('admins'));
    }
    public function create()
    {
        return view('owner.admins.create');
    }

    // Menyimpan data admin baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = new User();
        $admin->username = $request->username;
        $admin->telephone = $request->telephone;
        $admin->alamat = $request->alamat;
        $admin->role_id = 2;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('owner.admins.index')->with('success', 'Admin created successfully.');
    }

    // Menampilkan form untuk mengedit admin
    public function edit(User $admin)
    {
        return view('owner.admins.edit', compact('admin'));
    }

    // Mengupdate data admin di database
    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('owner.admins.index')->with('success', 'Admin updated successfully.');
    }

    // Menghapus admin dari database
    public function destroy(User $admin)
    {
        $admin->delete();

        return redirect()->route('owner.admins.index')->with('success', 'Admin deleted successfully.');
    }
}
