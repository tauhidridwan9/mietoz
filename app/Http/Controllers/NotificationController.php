<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    public function index()
    {
        // Fetch notifications for the authenticated user
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->get();

        return view('notifications.owner-notif', [
            'notifications' => $notifications
        ]);
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
