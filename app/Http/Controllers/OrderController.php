<?php

namespace App\Http\Controllers;

use App\Events\OrderUpdated;
use App\Notifications\OrderFinish;
use App\Mail\OrderFinishEmail;
use Illuminate\Support\Str;
use App\Mail\OrderCancelledNotification;
use App\Mail\OrderCompletedNotification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderCancelledNotification as NotificationsOrderCancelledNotification;
use App\Notifications\OrderProcessingDelivered;
use App\Notifications\OrderProcessingNotification;
use App\Notifications\OrderStatusUpdated;
use App\Notifications\StockDepletedNotification;
use Illuminate\Http\Request;
use Midtrans\Snap;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Midtrans\Transaction;

class OrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
   public function viewPdf($id)
{
    // Temukan order berdasarkan ID
    $order = Order::findOrFail($id);

    // Dapatkan link PDF yang disimpan (diasumsikan disimpan sebagai path relatif di kolom 'pdf_link')
    $pdfPath = asset('storage/' . $order->pdf_link);

    // Cek apakah file tersebut ada di storage (untuk memastikan validasi sebelum mengirim ke view)
    if (!file_exists(storage_path('app/public/' . $order->pdf_link))) {
        abort(404, 'PDF file not found.');
    }

    // Kembalikan view dengan path PDF yang dikirim ke view
    return view('pdf-viewer', compact('pdfPath'));
}
    public function viewResi($id)
    {
        // Temukan order berdasarkan ID
        $order = Order::findOrFail($id);

        // Dapatkan link PDF yang disimpan (diasumsikan disimpan sebagai path relatif di kolom 'pdf_link')
        $pdfPath = asset('storage/' . $order->resi);

        // Cek apakah file tersebut ada di storage (untuk memastikan validasi sebelum mengirim ke view)
        if (!file_exists(storage_path('app/public/' . $order->resi))) {
            abort(404, 'PDF file not found.');
        }

        // Kembalikan view dengan path PDF yang dikirim ke view
        return view('pdf-viewer-resi', compact('pdfPath'));
    }




    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
        ->orderBy('id', 'asc') // or any other column
        ->get();
        

        return view('orders.index', compact('orders'));
    }


    public function markAsDelivered($id)
    {
        // Find the order by ID or fail
        $order = Order::findOrFail($id);

        // Check if the order has a receipt (resi) in the 'resi' column, if necessary.
        // Assuming you don't need a resi check, we'll proceed directly to updating the status.

        // Change status to 'delivered'
        $order->status = 'delivered';
        $order->save();

        // Get the order items, ensure you have the correct relationship set up in your Order model
        $orderItems = $order->orderItems;

        // Get the customer (assuming a relationship exists between Order and User)
        $customer = $order->user;

        // Check if the customer email is not an @example.com address
        if (!str_contains($customer->email, '@example.com')) {
            // Send a notification to the customer
            $customer->notify(new OrderProcessingDelivered($order));

            // Send email to the customer, including the $orderItems
            Mail::to($customer->email)->send(new OrderCompletedNotification($orderItems));
        }

        event(new OrderUpdated([
            'countCooking' => Order::where('status', 'delivered')->count(),
        ]));


        // Redirect back to the orders management page with a success message
        return redirect()->route('orders.manage.cooking')->with('status', 'Pesanan telah dikirim dan email pemberitahuan dikirim ke customer!');
    }


public function markAsDiambil($id)
    {
        $order = Order::findOrFail($id);

            // Ubah status menjadi 'delivered'
            $order->status = 'completed';
            $order->save();

            // Ambil items dari order jika ada relasi ke order_items
            $orderItems = $order->orderItems; // Pastikan ada relasi yang benar antara Order dan Item

            // Kirim notifikasi ke customer
            $customer = $order->user; // Pastikan ada relasi ke model User di Order
            $customer->notify(new OrderFinish($order));



           

            // Redirect kembali ke halaman kelola pesanan dengan pesan status
            return redirect()->route('orders.manage.diambil')->with('status', 'Pesanan telah dikirim dan email pemberitahuan dikirim ke customer!');
        
    }


    public function accept($id)
{
    // Temukan order dengan orderItems terkait
    $order = Order::with('orderItems.product')->findOrFail($id);

        // Cek stok produk sebelum mengubah status menjadi 'processing'
        foreach ($order->orderItems as $item) {
            $product = $item->product;

            if ($product->stock <= 0) {
                $customer = $order->user;

                // Check if the customer's email is not an @example.com address
                if (!str_contains($customer->email, '@example.com')) {
                    // Send cancellation email and notification
                    Mail::to($customer->email)->send(new OrderCancelledNotification($order, $item));
                    $customer->notify(new NotificationsOrderCancelledNotification(
                        $order,
                        $product->name,
                        $item->quantity,
                        $order->total_amount
                    ));
                }

                // Change the order status to 'rejected'
                $order->status = 'rejected';
                $order->save();

                // Refund the order
                $this->refund($order->id, $order->total_amount);

                // Redirect back with an error message
                return redirect()->back()->with('error', 'Order ' . $order->id . ' for product ' . $product->name . ' has been cancelled due to insufficient stock.');
            }
        }

    // Semua stok mencukupi, ubah status menjadi 'processing'
    $order->status = 'processing';
    $order->save();

    // Generate PDF dan simpan ke storage
    $pdf = PDF::loadView('orders.receipt', ['order' => $order]);
    $pdfFileName = 'pdf/order_receipt_' . $order->id . '.pdf';
    $pdf->save(storage_path('app/public/' . $pdfFileName));
    $order->pdf_link = $pdfFileName;
    $order->save();

    // Kirim notifikasi ke customer
    $customer = $order->user;
    $customer->notify(new OrderProcessingNotification($order));

    // Dekrementasi stok produk untuk setiap item yang ada di order
    foreach ($order->orderItems as $item) {
        $item->product->decrement('stock', $item->quantity);
    }

        event(new OrderUpdated([
            'customerCount' => User::where('role_id', 1)->count(),
            'orderCount' => Order::where('status', 'paid')->count(),
            'countProcessing' => Order::where('status', 'cash')->count(),
            'countCooking' => Order::where('status', 'processing')->count(),
            'countDiambil' => Order::where('status', 'delivered')->count(),
        ]));

    return redirect()->back()->with('success', 'Order confirmed.');
}

 public function manageDiambil(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::with('user', 'orderItems')
        ->whereIn('status', ['delivered']) // Filter berdasarkan status 'processing'
        ->when($search, function ($query, $search) {
            // Pencarian berdasarkan nama user atau ID pesanan
            return $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%'); // Pencarian nama user
            })->orWhere('id', $search); // Pencarian berdasarkan ID pesanan
        })
            ->orderBy('updated_at', 'asc') // Urutkan berdasarkan waktu update terakhir
            ->get();

        event(new OrderUpdated([
            'customerCount' => User::where('role_id', 1)->count(),
            'orderCount' => Order::where('status', 'paid')->count(),
            'countProcessing' => Order::where('status', 'cash')->count(),
            'countCooking' => Order::where('status', 'processing')->count(),
            'countDiambil' => Order::where('status', 'delivered')->count(),
        ]));

        return view('orders.diambil', compact('orders'));
    }


    private function refund($id, $amount)
    {
        // Set Midtrans config
        \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Melakukan refund
        try {
            $params = [
                'refund_key' => uniqid(), // Kunci unik untuk refund
                'amount' => $amount,
                'reason' => 'Stock out'
            ];

            // Transaction::refund membutuhkan 2 argumen: transaction_id dan params
            $response = Transaction::refund($id, $params);

            // Memeriksa respon dari refund jika berupa array
            if (isset($response['status_code']) && $response['status_code'] === '200') {
                return $response; // Refund berhasil
            } else {
                Log::error('Refund failed: ' . json_encode($response));
                // Handle error jika refund gagal
                return null; // Kembalikan null atau respons sesuai kebutuhan
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Refund Error: ' . $e->getMessage());
            // Handle error jika refund gagal
            return null; // Kembalikan null atau respons sesuai kebutuhan
        }
    }









    public function manage(Request $request)
    {
        $search = $request->input('search');

        // Query untuk mendapatkan pesanan dengan status 'paid' atau 'cash'
        $orders = Order::with('user', 'orderItems')
        ->whereIn('status', ['paid']) // Filter berdasarkan status
        ->when($search, function ($query, $search) {
            // Pencarian berdasarkan nama user atau ID pesanan
            return $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('id', $search)->orWhere('status', ['paid','cash']); // Pencarian berdasarkan ID
        })
            ->orderBy('updated_at', 'asc') // Urutkan berdasarkan waktu terakhir diupdate
            ->get();

        return view('orders.manage', compact('orders'));
    }

    public function manageProcess(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::with('user', 'orderItems')
        ->whereIn('status', ['cash']) // Filter berdasarkan status 'processing'
        ->when($search, function ($query, $search) {
            // Pencarian berdasarkan nama user atau ID pesanan
            return $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%'); // Pencarian nama user
            })->orWhere('id', $search); // Pencarian berdasarkan ID pesanan
        })
            ->orderBy('updated_at', 'asc') // Urutkan berdasarkan waktu update terakhir
            ->get();

        return view('orders.processing', compact('orders'));
    }

    

    public function manageCooking(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::with('user', 'orderItems')
        ->whereIn('status', ['processing']) // Filter berdasarkan status 'processing'
        ->when($search, function ($query, $search) {
            // Pencarian berdasarkan nama user atau ID pesanan
            return $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%'); // Pencarian nama user
            })->orWhere('id', $search); // Pencarian berdasarkan ID pesanan
        })
            ->orderBy('updated_at', 'asc') // Urutkan berdasarkan waktu update terakhir
            ->get();

        return view('orders.cooking', compact('orders'));
    }

    public function failed($orderId)
    {
        // Cari order berdasarkan ID
        $order = Order::find($orderId);

        // Cek apakah order ada
        if (!$order) {
            return redirect()->route('home')->with('error', 'Order tidak ditemukan.');
        }

        // Tampilkan halaman dengan informasi order dan status failed
        return view('order.failed', [
            'order' => $order,
        ]);
    }
    public function pending($orderId)
    {
        // Cari order berdasarkan ID
        $order = Order::with('orderItems')->find($orderId);

        // Cek apakah order ada
        if (!$order) {
            return redirect()->route('home')->with('error', 'Order tidak ditemukan.');
        }

        // Pastikan status order adalah pending
        if ($order->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Order tidak dalam status pending.');
        }

        // Tampilkan halaman dengan informasi order dan status pending
        return view('orders.pending', [
            'order' => $order,
            'status' => 'pending',
        ]);
    }

    public function create()
    {
        // Display the form to create a new order

        $products = Product::all();
        $users = User::all();
        return view('orders.create', compact('products', 'users'));
    }

    public function storeAdmin(Request $request)
    {
        Log::info('Request data:', $request->all());

        // Validate the request
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0', // Validasi untuk total_amount
        ]);

        // Cari user berdasarkan nama, atau buat user baru jika belum ada
        $user = User::firstOrCreate(
            ['name' => $validated['customer_name']], // Kondisi pencarian (cari berdasarkan nama)
            [ // Data yang akan diisi jika pengguna tidak ditemukan
                'username' => $validated['customer_name'],
                'telephone' => '1234567891011',
                'alamat' => 'none',
                'role_id' => 1,
                'email' => Str::random(10) . '@example.com',
                'password' => bcrypt('password123')
            ]
        );

        // Get products
        $products = Product::whereIn('id', $validated['products'])->get();

        // Check stock availability
        foreach ($validated['products'] as $index => $productId) {
            $product = $products->where('id', $productId)->first();
            $requestedQuantity = $validated['quantities'][$index];

            if ($product->stock < $requestedQuantity) {
                // Return error response if stock is insufficient
                return redirect()->back()->with('error', 'Stok tidak cukup untuk produk: ' . $product->name . '. Mohon kurangi jumlah yang dipesan.');
            }
        }

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,  // Menggunakan ID user yang baru dibuat atau ditemukan
            'status' => 'cash',
            'total_amount' => $validated['total_amount']
        ]);

        // Add each product as an order item
        foreach ($validated['products'] as $index => $productId) {
            // Mendapatkan harga produk berdasarkan ID produk
            $product = $products->where('id', $productId)->first();

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $product->name, // Menyimpan nama produk
                'price' => $product->price, // Menyimpan harga produk
                'quantity' => $validated['quantities'][$index],
            ]);
        }

        $pdf = PDF::loadView('orders.resi', compact('order'));

        $pdfFileName = 'pdf/resi_' . $order->id . '.pdf'; // Relative path for 'public' disk
        $pdf->save(storage_path('app/public/' . $pdfFileName)); // Save PDF to storage/public/pdf/

        // Store the relative PDF path in the order record
        $order->resi = $pdfFileName;
        $order->save();
        event(new OrderUpdated([
            'customerCount'=> User::where('role_id', 1)->count(),
            'orderCount' => Order::where('status', 'paid')->count(),
            'countProcessing' => Order::where('status', 'cash')->count(),
            'countCooking' => Order::where('status', 'processing')->count(),
            'countDiambil' => Order::where('status', 'delivered')->count(),
        ]));
        Log::info('OrderUpdated event fired with customerCount:', ['customerCount' => User::count()]);



        return redirect()->route('orders.manage.process')->with('success', 'Pesanan berhasil ditambahkan.');
    }



    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $totalAmount = $product->price * $request->quantity;

        // Pastikan totalAmount adalah float dan lebih besar dari 0.01
        $totalAmount = floatval($totalAmount);
        if ($totalAmount < 0.01) {
            $totalAmount = 0.01;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        $this->notificationService->sendOrderNotification($request->user(), $order);

        // Generate payment token
        $snapToken = Snap::createTransaction([
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ]);

        return response()->json(['token' => $snapToken->token]);
    }
    public function showSuccess()
    {
        // Assuming you fetch the latest successful order of the logged-in user
        $order = Order::where('user_id', auth()->id())
            ->where('status', 'paid') // Assuming 'success' is the status for successful orders
            ->latest()
            ->with('orderItems')
            ->first();

        // If no order is found, handle it (optional)
        if (!$order) {
            return redirect()->back()->with('error', 'No successful order found.');
        }
       

        return view('orders.success', compact('order'));
    }

    public function success()
    {
        // Temukan order berdasarkan ID
        $orders = Order::where('user_id', auth()->id())->get();
        
        // Tampilkan halaman sukses dengan data order
        return view('orders.success', [
            'order' => $orders,
        ]);
    }


    public function show(Order $order)

    {

        // Eager load the 'items' relationship

        $order->load('orderItems');
        $pdfPath = asset('storage/' . $order->resi);
        $pdf_link = asset('storage/' . $order->pdf_link);


        // Display the details of a specific order

        return view('orders.show', compact('order', 'pdfPath', 'pdf_link'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Validate and update the order status
        $order->status = $request->input('status');
        $order->save();

        // Ensure the order has a valid customer
        if ($order->customer) {
            // Send notification
            Notification::send($order->customer, new OrderStatusUpdated($order));
        } else {
            // Handle the case where the customer is not found
            // Log an error or notify an admin, depending on your use case
            Log::error('Customer not found for order ID ' . $order->id);
        }

        return redirect()->route('orders.manage')->with('status', 'Order status updated!');
    }
    public function confirmOrder($id)
    {
        // Fetch the order by ID
        $order = Order::findOrFail($id);

        // Update the order status to 'completed'
        $order->status = 'completed';
        $order->save();

        // Fetch the user
        $user = $order->user; // Assuming you have a relation to get the user

        // Delete the notification for this order
        $user->notifications()->where('data->order_id', $order->id)->delete(); // Adjust based on how you store order ID in notification data

        // Redirect with success message
        return redirect()->route('notifications.index')->with('success', 'Pesanan telah dikonfirmasi dan status telah diubah menjadi completed.');
    }

    public function calculator(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $nominal = $request->input('nominal');
        $total_amount = $request->input('tagihan');
        $kembalian = $nominal - $total_amount;

        if ($nominal >= $total_amount) {
            // Loop melalui setiap item dalam order untuk mengurangi stok produk
            foreach ($order->orderItems as $orderItem) {
                // Ambil produk terkait dari setiap item
                $product = Product::findOrFail($orderItem->product_id);

                // Kurangi stok berdasarkan jumlah yang dipesan
                if ($product->stock >= $orderItem->quantity) {
                    // Check if stock will go to zero
                    $product->stock -= $orderItem->quantity;
                    $product->save(); // Simpan perubahan stok
                    if ($product->stock <= 0) {
                        // Notify user with role_id 3 about the stock depletion
                        $adminStock = User::where('role_id', 3)->first(); // Fetch admin with role_id 3
                        $adminStock->notify(new StockDepletedNotification($product)); // Send stock depleted notification
                    }
                } else {
                    // Jika stok produk tidak mencukupi
                    session()->flash('error', "Stok tidak mencukupi untuk produk {$product->name}.");
                    return redirect()->route('orders.payment', $id);
                }
            }

            // Ubah status order menjadi 'delivered'
            $order->status = 'completed';
            $order->save();

            // Flash pesan sukses
            session()->flash('success', 'Berhasil melakukan pembayaran dan stok produk telah dikurangi.');
            return view('orders.cash-payment', compact('order', 'kembalian'));
        } else {
            // Flash pesan error jika nominal kurang dari total tagihan
            session()->flash('error', 'Nominal uang tidak cukup!');
            return redirect()->route('orders.payment', $id);
        }
    }





}

