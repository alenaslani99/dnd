<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackOrderController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');

Route::get('brendovi', [BrandController::class, 'index'])->name('brands.index');

Route::get('parfemi', [ProductController::class, 'index'])->name('products.index');
Route::get('parfemi/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('throttle:cart')->group(function () {
    Route::get('korpa', [CartController::class, 'index'])->name('cart.index');
    Route::post('korpa', [CartController::class, 'store'])->name('cart.store');
    Route::patch('korpa/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('korpa/{item}', [CartController::class, 'destroy'])->name('cart.destroy');
});

Route::middleware('throttle:checkout')->group(function () {
    Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::get('porudzbine/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');

Route::get('prati-porudzbinu', [TrackOrderController::class, 'create'])->name('track-order.create');
Route::post('prati-porudzbinu', [TrackOrderController::class, 'store'])->name('track-order.store');

Route::get('kontakt', [ContactController::class, 'create'])->name('contact.create');
Route::post('kontakt', [ContactController::class, 'store'])->name('contact.store');

Route::middleware(['guest', 'throttle:auth'])->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('proizvodi', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('porudzbine', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('porudzbine/{orderNumber}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('porudzbine/{orderNumber}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');
});
