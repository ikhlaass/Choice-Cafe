<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\CartController;

class OrderController extends Controller
{
    /**
     * Display the checkout page
     */
    public function create()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart is empty!');
        }

        $total = CartController::calculateTotal();

        return view('orders.create', compact('cart', 'total'));
    }

    /**
     * Store the order and create Midtrans payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|numeric',
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart is empty!');
        }

        $items = array_values($cart);
        $total = $request->input('total_amount');
        $tableNumber = Session::get('table_number', '01');

        // Generate transaction code
        $transactionCode = 'JV' . date('ymdHis') . rand(100, 999);

        // Create Midtrans payment
        $midtransService = new \App\Services\MidtransService();
        $result = $midtransService->createPayment([
            'transaction_code' => $transactionCode,
            'amount' => $total,
            'table_number' => $tableNumber,
            'items' => $items,
        ]);

        if (!$result['success']) {
            \Log::error('Midtrans Payment Failed', ['result' => $result]);
            return redirect()->back()
                ->with('error', 'Payment gateway error: ' . ($result['error'] ?? 'Unknown error'));
        }

        // Store order info in session
        Session::put('pending_order', [
            'transaction_code' => $transactionCode,
            'total_amount' => $total,
            'payment_method' => 'QRIS',
            'items' => $items,
            'status' => 'pending_payment',
            'estimated_time' => '15 Min',
            'snap_token' => $result['snap_token'] ?? null,
            'expiry_date' => now()->addHour()->toIso8601String(),
        ]);

        // Clear cart
        Session::forget('cart');

        // Redirect to payment page
        return redirect()->route('payment.show', ['transaction' => $transactionCode]);
    }

    /**
     * Display order success page
     */
    public function success()
    {
        $order = Session::get('last_order');
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'No order found!');
        }

        return view('orders.success', compact('order'));
    }
}