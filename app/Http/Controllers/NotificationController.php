<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Cek role_id user
        if ($user->role_id == 3) {
            // Jika role_id adalah 3, arahkan ke owner-notif
            $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();

            return view('notifications.owner-notif', [
                'notifications' => $notifications
            ]);
        } elseif ($user->role_id == 1) {
            // Jika role_id adalah 1, arahkan ke index
            $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();

            return view('notifications.index', [
                'notifications' => $notifications
            ]);
        }

        // Jika role_id tidak sesuai, Anda bisa menambahkan aksi default atau redirect
        return redirect()->back()->with('error', 'Role not authorized');
    }

    public function clearAll()
    {
        // Hapus semua notifikasi untuk pengguna yang sedang login
        auth()->user()->notifications()->delete();

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Semua notifikasi telah dihapus.');
    }
    public function markAsRead($id)
    {
        // Find the notification and mark it as read
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete(); // Remove the notification from the database

        return redirect()->back()->with('success', 'Notification deleted.');
    }
}
