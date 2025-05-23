<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\OrderFileController;
use App\Http\Controllers\OrderCancellationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShopifyWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return redirect('/login');
});
Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/');
});


Route::get('/auth/shopify', [ShopifyAuthController::class, 'redirectToShopify'])->name('shopify.login');
Route::get('/authenticate', [ShopifyAuthController::class, 'handleShopifyCallback']);
Route::get('/logout', [ShopifyAuthController::class, 'logout'])->name('logout');


Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/shopify/login', [ShopifyAuthController::class, 'redirectToShopify']);
    Route::get('/shopify/callback', [ShopifyAuthController::class, 'handleShopifyCallback']);
    Route::get('/sync-orders', [OrderController::class, 'sync']);

    Route::get('/customer/orders', [CustomerOrderController::class, 'index'])->name('customer.orders');
    Route::get('/customer/orders/{order}', [CustomerOrderController::class, 'show'])->name('customer.order.details');

});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::post('/admin/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/orders/{order}/upload', [OrderFileController::class, 'upload'])->name('orders.upload');
    Route::get('/files/{file}/download', [OrderFileController::class, 'download'])->name('files.download');
    Route::delete('/files/{file}/delete', [OrderFileController::class, 'delete'])->name('files.delete');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/orders/{order}/request-cancel', [OrderCancellationController::class, 'requestCancellation'])->name('orders.cancel.request');

    Route::middleware(['admin'])->group(function () {
        Route::post('/admin/orders/{order}/approve-cancel', [OrderCancellationController::class, 'approve'])->name('orders.cancel.approve');
        Route::post('/admin/orders/{order}/reject-cancel', [OrderCancellationController::class, 'reject'])->name('orders.cancel.reject');
        Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports');
    });
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});


Route::post('/webhooks/orders/update', [ShopifyWebhookController::class, 'handleOrderUpdate']);
Route::post('/webhooks/customers/update', [ShopifyWebhookController::class, 'handleCustomerUpdate']);
