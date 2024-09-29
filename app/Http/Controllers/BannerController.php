<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $file = $request->file('banner');
        $path = $file->store('banners', 'public');

        $banner = new Banner();
        $banner->path = $path;
        $banner->save();

        return back()->with('success', 'Banner uploaded successfully!');
    }
    public function getBanner()
    {
        return Banner::latest()->first();
    }
    public function index()
    {
        $banners = Banner::all();
        return view('banners.index', compact('banners'));
    }

    // Method untuk menampilkan form tambah banner
    public function create()
    {
        return view('banners.create');
    }

    // Method untuk menyimpan banner baru
    public function store(Request $request)
    {
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Simpan file banner ke storage
        $path = $request->file('banner')->store('banners', 'public');

        // Simpan data ke database
        Banner::create([
            'path' => $path,
        ]);

        return redirect()->route('banner.index')->with('success', 'Banner berhasil diupload.');
    }

    // Method untuk menampilkan form edit banner
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('banners.edit', compact('banner'));
    }

    // Method untuk mengupdate banner
    public function update(Request $request, $id)
    {
        $request->validate([
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $banner = Banner::findOrFail($id);

        // Jika ada file baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('banner')) {
            Storage::disk('public')->delete($banner->image_path);  // Hapus file lama
            $path = $request->file('banner')->store('banners', 'public');
            $banner->image_path = $path;
        }

        $banner->save();

        return redirect()->route('banner.index')->with('success', 'Banner berhasil diupdate.');
    }

    // Method untuk menghapus banner
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        Storage::disk('public')->delete($banner->path); // Hapus file dari storage
        $banner->delete();

        return redirect()->route('banner.index')->with('success', 'Banner berhasil dihapus.');
    }
}

