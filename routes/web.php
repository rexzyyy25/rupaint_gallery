<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AdminController,AuthController,CertificateController,HomeController,OrderController,PaintingController};

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/admin/login', [AuthController::class, 'adminLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->middleware('throttle:5,1')->name('admin.login.store');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/payments/midtrans/notification', [OrderController::class, 'notification'])
    ->name('payments.midtrans.notification');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/gallery', [PaintingController::class, 'index'])->name('gallery.index');
    Route::get('/gallery/{painting}', [PaintingController::class, 'show'])->name('gallery.show');
    Route::get('/painter/certificate', [CertificateController::class, 'show'])->name('painter.certificate');
    Route::get('/my/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/order', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/order/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
    Route::post('/order/{order}/payment/retry', [OrderController::class, 'retryPayment'])->name('orders.payment.retry');
    Route::get('/order/{order}/whatsapp', [OrderController::class, 'whatsapp'])->name('orders.whatsapp');
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', fn () => redirect()->route('admin.paintings.index'))->name('dashboard');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/create', [AdminController::class, 'createOrder'])->name('orders.create');
        Route::post('/orders', [AdminController::class, 'storeOrder'])->name('orders.store');
        Route::get('/orders/{order}/edit', [AdminController::class, 'editOrder'])->name('orders.edit');
        Route::put('/orders/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
        Route::delete('/orders/{order}', [AdminController::class, 'destroyOrder'])->name('orders.destroy');
        Route::patch('/orders/{order}/status', [AdminController::class, 'updateStatus'])->name('orders.status');
        Route::get('/certificate', [AdminController::class, 'certificate'])->name('certificate.edit');
        Route::put('/certificate', [AdminController::class, 'updateCertificate'])->name('certificate.update');
        Route::resource('paintings', AdminController::class)->except(['show']);
    });
});
