<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Owner\ManageAdminController;
use App\Http\Controllers\Owner\UserController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;







/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/banner', [BannerController::class, 'getBanner'])->name('banner.get');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/midtrans/callback', [PaymentController::class, 'handleCallback'])->name('midtrans.callback');
Route::get('/products/{id}', [ProductController::class, 'detail'])->name('products.show');



Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Route::get('admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index'); // Menampilkan daftar kategori
    // Route::get('admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create'); // Menampilkan form tambah kategori
    // Route::post('admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store'); // Menyimpan kategori baru
    // Route::get('admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit'); // Menampilkan form edit kategori
    // Route::put('admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update'); // Memperbarui kategori
    // Route::delete('admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy'); // Menghapus kategori
    // // Route::resource('products', ProductController::class);
    // Route::post('banner', [BannerController::class, 'upload']);
    // Route::get('admin/products/create',[ProductController::class, 'create'])->name('products.create');
    // Route::post('admin/products/store', [ProductController::class, 'store'])->name('products.store');
    // Route::get('admin/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    // Route::delete('admin/products/delete/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    // Route::put('admin/products/edit/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders/manage', [OrderController::class, 'manage'])->name('orders.manage');
    Route::get('/orders/manage/process', [OrderController::class, 'manageProcess'])->name('orders.manage.process');
    Route::get('/orders/manage/cooking', [OrderController::class, 'manageCooking'])->name('orders.manage.cooking');
    Route::patch('/admin/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
    Route::delete('/admin/notifications/{id}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');
    Route::delete('/admin/notifications', [NotificationController::class, 'clearAll'])->name('admin.notifications.clearAll');
    Route::post('/orders/{id}/delivered', [OrderController::class, 'markAsDelivered'])->name('orders.delivered');
    // routes/web.php

    Route::get('/orders/{id}/payment', [OrderController::class, 'manageProcess'])->name('orders.payment');

    Route::get('/orders/pdf/{id}', [OrderController::class, 'viewPdf'])->name('orders.pdf');
    // Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/admin/chat', [ChatController::class, 'adminIndex'])->name('admin.chat.index');
    Route::post('/admin/chat/{chatId}/reply', [ChatController::class, 'adminReply'])->name('admin.chat.reply');
    Route::get('/admin/chat/{user_id}', [ChatController::class, 'show'])->name('admin.chat');
    Route::post('admin/cash/payment/{orders}', [OrderController::class, 'calculator'])->name('cash.payment.process');


   
});
Route::middleware(['auth', 'role:owner'])->group(function () {
    // Dashboard Owner
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index'); // Menampilkan daftar kategori
    Route::get('admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create'); // Menampilkan form tambah kategori
    Route::post('admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store'); // Menyimpan kategori baru
    Route::get('admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit'); // Menampilkan form edit kategori
    Route::put('admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update'); // Memperbarui kategori
    Route::delete('admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy'); // Menghapus kategori
    // Route::resource('products', ProductController::class);
    Route::post('banner', [BannerController::class, 'upload']);
    Route::get('admin/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('admin/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('admin/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::delete('admin/products/delete/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::put('admin/products/edit/{product}', [ProductController::class, 'update'])->name('products.update');

    // Manage Users
    Route::get('/owner/users', [UserController::class, 'index'])->name('owner.users.index');

    // Manage Admins
    Route::get('/owner/admins', [ManageAdminController::class, 'index'])->name('owner.admins.index');
    Route::post('/owner/admins/store', [ManageAdminController::class, 'store'])->name('owner.admins.store');
    Route::get('/owner/admins/create', [ManageAdminController::class, 'create'])->name('owner.admins.create');
    Route::get('/owner/admins/{admin}/edit', [ManageAdminController::class, 'edit'])->name('owner.admins.edit'); // Form Edit Admin
    Route::put('/owner/admins/{admin}', [ManageAdminController::class, 'update'])->name('owner.admins.update'); // Proses Update Admin
    Route::delete('/owner/admins/{admin}', [ManageAdminController::class, 'destroy'])->name('owner.admins.destroy'); // Hapus Admin
    // routes/web.php
    Route::get('/notifications/owners', [NotificationController::class, 'index'])->name('notifications.owners.index');
    Route::patch('/owners/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('owner.notifications.read');
    Route::delete('/owners/notifications/{id}', [NotificationController::class, 'destroy'])->name('owner.notifications.destroy');
    Route::delete('/owners/notifications', [NotificationController::class, 'clearAll'])->name('owner.notifications.clearAll');
    // Rute untuk menampilkan halaman banner
    Route::resource('banner', BannerController::class);

    // Reports
    Route::get('/owner/reports', [OwnerController::class, 'reports'])->name('owner.reports'); // Laporan Pendapatan
});


Route::group(['middleware' => 'auth'], function () {
    Route::delete('/customer/notifications', [NotificationController::class, 'clearAll'])->name('customer.notifications.clearAll');
    Route::resource('orders', OrderController::class);
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/cart', [CartController::class, 'cart'])->name('cart.index');
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/cart/{productId}', [CartController::class, 'update'])->name('cart.update');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/payment', [PaymentController::class, 'pay'])->name('payment.pay');
    // Rute untuk menyelesaikan pembayaran, jika Anda menggunakan metode GET
    // Route
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/cash', [PaymentController::class, 'cashPayment'])->name('payment.cash');

    
    Route::get('/orders/pending/{orderId}', [OrderController::class, 'pending'])->name('orders.pending');
    Route::get('/orders/failed/{orderId}', [OrderController::class, 'failed'])->name('orders.failed');
    Route::get('/order/success', [OrderController::class, 'showSuccess'])->name('order.success');
    Route::post('admin/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::post('profile/{id}/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    

    // routes/web.php

    Route::get('/orders/confirm/{id}', [OrderController::class, 'confirmOrder'])->name('orders.confirm');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    // Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/messages', [ChatController::class, 'fetchMessages'])->name('chat.fetch');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
    Route::get('cash/payment/{order_id}', [CheckoutController::class, 'cash'])->name('cash.payment');
    Route::post('cash/payment/confirm/{order_id}',[CheckoutController::class, 'confirm'])->name('cash.payment.confirm');
    Route::get('cash/payment/pdf/{order_id}', [CheckoutController::class, 'pdf'])->name('cash.payment.pdf');
    




});


