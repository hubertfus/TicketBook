<?php

use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\TicketPurchaseController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderItemController as AdminOrderItemController;
use App\Http\Controllers\Admin\RefundController as AdminRefundController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminTopUpController;
use App\Http\Controllers\TopUpRedemptionController;
use App\Http\Controllers\User\PaymentController;


Route::get('/', function () {
    return view('pages.landingpage');
});

Route::middleware(['auth', 'check.roles:admin'])->prefix('admin')->group(function () {
    Route::resource('events', AdminEventController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::resource('order-items', AdminOrderItemController::class);
    Route::get('/top-up-codes/create', [AdminTopUpController::class, 'create'])->name('admin.topup.create');
    Route::post('/top-up-codes/store', [AdminTopUpController::class, 'store'])->name('admin.topup.store');
    Route::resource('users', UserController::class);
    Route::get('/refunds', [AdminRefundController::class, 'index'])->name('refunds.index');
    Route::post('/refunds/{refund}/approve', [AdminRefundController::class, 'approve'])->name('refunds.approve');
    Route::post('/refunds/{refund}/reject', [AdminRefundController::class, 'reject'])->name('refunds.reject');
    Route::resource('reviews', ReviewController::class)->names(['index' => 'admin.reviews.index','show' => 'admin.reviews.show','edit' => 'admin.reviews.edit','update' => 'admin.reviews.update','destroy' => 'admin.reviews.destroy']);
});

Route::middleware(['auth', 'check.roles:user'])->group(function () {
    Route::get('/top-up', [TopUpRedemptionController::class, 'showForm'])->name('topup.form');
    Route::post('/top-up', [TopUpRedemptionController::class, 'redeem'])->name('topup.redeem');
    Route::get('/my-topup-codes', [TopUpRedemptionController::class, 'index'])->name('topup.index');
    Route::get('/payment/{event}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{event}', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::post('/top-up/redeem-direct/{code}', [TopUpRedemptionController::class, 'redeemDirect'])
        ->name('topup.redeemDirect');
    Route::get('/orders', [UserOrderController::class, 'index'])->name('user.orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/refund', [UserOrderController::class, 'refund'])->name('orders.refund');
    Route::post('/orders/{order}/refund-request', [UserOrderController::class, 'submitRefundRequest'])->name('orders.refund.request');
});

Route::post('/events/{event}/add-review', [ReviewController::class, 'store'])->name('reviews.store');


Route::get('/events/{event}/buy', [TicketPurchaseController::class, 'create'])->name('tickets.buy');
Route::post('/events/{event}/buy', [TicketPurchaseController::class, 'store'])->name('tickets.store');
Route::resource('events', UserEventController::class)->only(['index', 'show']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
