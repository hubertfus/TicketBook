<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TicketPurchaseController;
use App\Http\Controllers\TopUpRedemptionController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\Auth\PasswordResetController;

use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderItemController as AdminOrderItemController;
use App\Http\Controllers\Admin\RefundController as AdminRefundController;
use App\Http\Controllers\User\ReviewController as UserReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\AdminTopUpController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;

// Landing page
Route::get('/', function () {
    return view('pages.landingpage');
});

// AUTH routes (common)
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// User routes
Route::middleware(['auth', 'check.roles:user'])->prefix('user')->name('user.')->group(function () {

    // Account management
    Route::get('/account', [AccountController::class, 'edit'])->name('profile.edit');
    Route::put('/account', [AccountController::class, 'update'])->name('profile.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('profile.destroy');

    // Top-up
    Route::get('/top-up', [TopUpRedemptionController::class, 'showForm'])->name('topup.form');
    Route::post('/top-up', [TopUpRedemptionController::class, 'redeem'])->name('topup.redeem');
    Route::get('/my-topup-codes', [TopUpRedemptionController::class, 'index'])->name('topup.index');
    Route::post('/top-up/redeem-direct/{code}', [TopUpRedemptionController::class, 'redeemDirect'])->name('topup.redeemDirect');

    // Orders
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', action: [UserOrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/refund', action: [UserOrderController::class, 'refund'])->name('orders.refund');
    Route::post('/orders/{order}/refund-request', [UserOrderController::class, 'submitRefundRequest'])->name('orders.refund.request');
    Route::get('/orders/{order}/confirmation', [UserOrderController::class, 'downloadConfirmation'])->name('orders.confirmation');

    // Payment
    Route::get('/payment/{event}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{event}', [PaymentController::class, 'pay'])->name('payment.pay');

    // Buy Ticket
    Route::post('/events/{event}/buy', [TicketPurchaseController::class, 'store'])->name('tickets.store');
});


// Admin routes
Route::middleware(['auth', 'check.roles:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Events
    Route::resource('events', AdminEventController::class);

    // Users
    Route::resource('users', UserController::class);

    // Orders & Items
    Route::resource('orders', AdminOrderController::class);
    Route::resource('order-items', AdminOrderItemController::class);

    // Refunds
    Route::get('refunds', [AdminRefundController::class, 'index'])->name('refunds.index');
    Route::post('refunds/{refund}/approve', [AdminRefundController::class, 'approve'])->name('refunds.approve');
    Route::post('refunds/{refund}/reject', [AdminRefundController::class, 'reject'])->name('refunds.reject');

    // Reviews
    Route::resource('reviews', AdminReviewController::class)->names([
        'index' => 'reviews.index',
        'show' => 'reviews.show',
        'edit' => 'reviews.edit',
        'update' => 'reviews.update',
        'destroy' => 'reviews.destroy',
    ]);

    // Tickets
    Route::get('events/{event}/tickets', [AdminTicketController::class, 'byEvent'])->name('tickets.byEvent');
    Route::get('events/{event}/tickets/create', [AdminTicketController::class, 'create'])->name('tickets.create');
    Route::post('events/{event}/tickets', [AdminTicketController::class, 'store'])->name('tickets.store');
    Route::resource('tickets', AdminTicketController::class)->except(['create', 'store', 'show']);

    // Top-Up codes (admin)
    Route::get('top-up-codes/create', [AdminTopUpController::class, 'create'])->name('topup.create');
    Route::post('top-up-codes/store', [AdminTopUpController::class, 'store'])->name('topup.store');
});

// Public routes (no auth)
Route::post('/events/{event}/add-review', [UserReviewController::class, 'store'])->name('reviews.store');
Route::delete('/reviews/{review}', [UserReviewController::class, 'destroy'])->name('reviews.destroy');
Route::get('/events/{event}/buy', [TicketPurchaseController::class, 'create'])->name('tickets.buy');
Route::post('/events/{event}/buy', [TicketPurchaseController::class, 'store'])->name('tickets.purchase');


Route::prefix('events')->name('user.events.')->group(function () {
    Route::get('/', [UserEventController::class, 'index'])->name('index');
    Route::get('/{event}', [UserEventController::class, 'show'])->name('show');
});
