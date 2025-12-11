<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes - Choice Self Order System (Optimized)
|--------------------------------------------------------------------------
*/

// ============================================
// HOME / MENU LIST
// ============================================
Route::get('/', [MenuController::class, 'index'])->name('home');


// ============================================
// CART ACTIONS
// ============================================
// Add item to cart
Route::post('/cart/add', [CartController::class, 'add'])->name('menu.addToCart');

// Update quantity
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');

// Update notes (AJAX)
Route::post('/cart/update-notes/{id}', [CartController::class, 'updateNotes'])->name('cart.updateNotes');

// Remove item from cart
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');


// ============================================
// CHECKOUT & ORDERS
// ============================================
// Display checkout page
Route::get('/checkout', [OrderController::class, 'create'])->name('checkout');

// Submit order (QRIS only)
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

// Display success page
Route::get('/order/success', [OrderController::class, 'success'])->name('orders.success');


/*
|--------------------------------------------------------------------------
| CHANGELOG:
|--------------------------------------------------------------------------
| ✅ Removed Dine In/Take Away toggle
| ✅ Removed payment method selection modal (QRIS only)
| ✅ Eliminated duplicate code between CartController and OrderController
| ✅ Simplified quantity update (no query string)
| ✅ Clean, efficient routing structure
|--------------------------------------------------------------------------
*/