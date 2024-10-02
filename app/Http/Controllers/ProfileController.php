<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;




class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        Log::info('Request data:', $request->all());

        // Jika user tidak ditemukan
        if (!$user) {
            return redirect()->route('profile.edit')->with('error', 'User not found!');
        }

        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'bio' => 'nullable|string|max:255',
            'profile_pictures' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update field user
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->telephone = $request->input('telephone');
        $user->alamat = $request->input('alamat');
        $user->email = $request->input('email');
        $user->bio = $request->input('bio');

        // Proses file gambar jika ada gambar yang diunggah
        if ($request->hasFile('profile_pictures')) {
            // Simpan gambar baru dan perbarui kolom profile_pictures
            $imagePath = $request->file('profile_pictures')->store('profile_pictures', 'public');
            $user->profile_pictures = $imagePath;
        }

        // Simpan perubahan
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }


}
