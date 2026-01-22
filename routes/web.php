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


// ============================================
// PAYMENT (XENDIT QRIS)
// ============================================
use App\Http\Controllers\PaymentController;

// Show payment page
Route::get('/payment/{transaction}', [PaymentController::class, 'show'])->name('payment.show');

// Payment success redirect
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');

// Payment failed redirect
Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');

// Xendit webhook
Route::post('/xendit/webhook', [PaymentController::class, 'webhook'])->name('xendit.webhook');

// Check payment status (AJAX)
Route::post('/payment/check-status', [PaymentController::class, 'checkStatus'])->name('payment.checkStatus');


// ============================================
// QR TABLE SYSTEM
// ============================================
use App\Http\Controllers\TableController;

// Customer scans QR code
Route::get('/table/{number}', [TableController::class, 'scan'])->name('table.scan');

// QR Generator page (for owner to generate QR codes)
Route::get('/qr-generator', [TableController::class, 'qrGenerator'])->name('qr.generator');

// Download single QR code
Route::get('/qr-download/{number}', [TableController::class, 'downloadQR'])->name('qr.download');

// Download all QR codes as ZIP
Route::get('/qr-download-all', [TableController::class, 'downloadAllQR'])->name('qr.downloadAll');


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

// Simulate payment success (for demo/testing)
Route::post('/payment/simulate-success', [PaymentController::class, 'simulateSuccess'])->name('payment.simulate-success');
