<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');

Route::get('brendovi', [BrandController::class, 'index'])->name('brands.index');

Route::get('parfemi', [ProductController::class, 'index'])->name('products.index');
Route::get('parfemi/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('korpa', [CartController::class, 'index'])->name('cart.index');
Route::post('korpa', [CartController::class, 'store'])->name('cart.store');
Route::patch('korpa/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('korpa/{item}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('porudzbine/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);

    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
});
