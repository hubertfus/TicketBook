<?php

use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\OrderItemController as UserOrderItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderItemController as AdminOrderItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.landingpage');
});

Route::middleware(['auth', 'check.roles:admin'])->prefix('admin')->group(function () {
    Route::resource('events', AdminEventController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::resource('order-items', AdminOrderItemController::class);

});

Route::middleware(['auth', 'check.roles:user'])->group(function () {
        Route::get('/orders', [UserOrderController::class, 'index'])->name('user.orders.index');
        Route::get('/orders/{order}', [UserOrderItemController::class, 'show'])->name('orders.details');
});


Route::resource('events', UserEventController::class)->only(['index', 'show']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
