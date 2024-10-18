<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Models\Chat;
use App\Models\User;
use App\Notifications\ChatNotification;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function show($user_id = null)
    {
        // Ambil semua user yang pernah terlibat dalam chat, baik admin atau user biasa
        $contacts = User::whereIn('id', Chat::pluck('user_id')->unique())->get();
        $contacts->each(function ($contact) {
            $contact->unreadCount = Chat::where('user_id', $contact->id)
                ->where('is_read', false)
                ->count();
        });

        // Jika tidak ada user_id, ambil user pertama dari kontak, jika tidak ada, beri null
        if (!$user_id && $contacts->isNotEmpty()) {
            $user_id = $contacts->first()->id;
        }

        // Ambil semua chat dari user yang dipilih atau set kosong jika tidak ada user yang dipilih
        $currentChat = $user_id ? Chat::where('user_id', $user_id)->get() : collect([]);

        // Ambil detail user yang sedang di-chat
        $selectedUser = $user_id ? User::find($user_id) : null;

        // Ubah semua chat dengan is_read = false menjadi is_read = true untuk user yang dipilih
        if ($user_id) {
            Chat::where('user_id', $user_id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('chat.admin', compact('contacts', 'currentChat', 'selectedUser'));
    }


    public function index()
    {
        // Jalankan clearAll logic
        auth()->user()->notifications()->delete();

        // Ambil chat untuk user yang sedang login
        $chats = Chat::where('user_id', Auth::id())->get();

        // Kembali ke halaman dengan chat data
        return view('chat.index', compact('chats'))->with('success', 'Semua notifikasi telah dihapus.');
    }



    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id' // Validasi user_id
        ]);

        // Cek apakah pengguna adalah admin
        $isAdmin = Auth::user()->role->name === 'admin';

        // Simpan pesan chat
        $chat = Chat::create([
            'user_id' => $request->user_id, // Menggunakan user_id dari request
            'message' => $request->message,
            'is_admin' => $isAdmin, // Jika admin, nilai is_admin akan true, jika bukan maka false
        ]);

        Log::info('Message Event Triggered', [
            'chat_id' => $chat->id,
            'message' => $request->message,
            'user_id' => $request->user_id,
        ]);

        // Jika pengirim bukan admin, kirim notifikasi ke admin
        if (!$isAdmin) {
            $admin = User::whereHas('role', function ($query) {
                $query->where('name', 'admin');
            })->first(); // Ambil admin pertama yang ada

            if ($admin) {
                $admin->notify(new ChatNotification($request->message, false));
            }
        } else {
            // Jika pengirim adalah admin, kirim notifikasi ke pengguna yang dituju
            $user = User::find($request->user_id); // Ambil user berdasarkan user_id dari request
            if ($user) {
                $user->notify(new ChatNotification($request->message, true));
                // event(new MessageEvent($chat->id, $request->message, $request->user_id));// true menunjukkan pesan dari admin
            }
        }

        return redirect()->back();
    }


    public function adminIndex()
    {
        // Menampilkan semua chat yang ada untuk admin
        $contacts = User::whereIn('id', Chat::pluck('user_id')->unique())->get();

        // Menambahkan unreadCount ke setiap contact
        $contacts->each(function ($contact) {
            $contact->unreadCount = Chat::where('user_id', $contact->id)
                ->where('is_read', false)
                ->count();
        });

        $selectedUser = $contacts->isNotEmpty() ? $contacts->first() : null;
        $chats = Chat::where('user_id', Auth::id())->get();

        // Ambil chat pertama jika ada
        $currentChat = $selectedUser ? Chat::where('user_id', $selectedUser->id)->get() : collect([]);

        return view('chat.admin', compact('contacts', 'currentChat', 'selectedUser', 'chats'));
    }



    public function adminReply(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Dapatkan user_id dari chat yang ada
        $chat = Chat::findOrFail($chatId);

        // Simpan balasan admin
        Chat::create([
            'user_id' => $chat->user_id,
            'message' => $request->message,
            'is_admin' => true,
        ]);

        // Kirim notifikasi ke customer
        $customer = User::find($chat->user_id); // Ambil pengguna dari user_id chat
        if ($customer) {
            $customer->notify(new ChatNotification($request->message, true));
           
            
        }
       

        return redirect()->back();
    }
}
