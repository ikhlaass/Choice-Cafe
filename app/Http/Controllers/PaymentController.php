<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    /**
     * Show payment page with QRIS
     */
    public function show($transaction)
    {
        $order = Session::get('pending_order');
        
        if (!$order || $order['transaction_code'] !== $transaction) {
            return redirect()->route('home')->with('error', 'Order not found!');
        }

        return view('payment.qris', compact('order'));
    }

    /**
     * Payment success callback
     */
    public function success()
    {
        $order = Session::get('pending_order');
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'No order found!');
        }

        // Move to completed order
        Session::put('last_order', array_merge($order, [
            'status' => 'cooking'
        ]));
        Session::forget('pending_order');

        return redirect()->route('orders.success')
            ->with('success', 'Payment successful!');
    }

    /**
     * Payment failed callback
     */
    public function failed()
    {
        return redirect()->route('home')
            ->with('error', 'Payment failed or cancelled. Please try again.');
    }

    /**
     * Simulate payment success (for demo/testing)
     */
    public function simulateSuccess()
    {
        $order = Session::get('pending_order');
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'No order found!');
        }

        // Mark as completed
        Session::put('last_order', array_merge($order, [
            'status' => 'cooking',
            'payment_status' => 'paid',
            'payment_method' => 'QRIS (Simulated)',
        ]));
        Session::forget('pending_order');

        return redirect()->route('orders.success')
            ->with('success', 'Payment simulated successfully!');
    }

    /**
     * Webhook handler (placeholder for future payment gateway)
     */
    public function webhook(Request $request)
    {
        \Log::info('Payment Webhook Received',['data' => $request->all()]);
        return response()->json(['success' => true]);
    }

    /**
     * Check payment status via AJAX (placeholder)
     */
    public function checkStatus(Request $request)
    {
        return response()->json([
            'success' => true,
            'status' => 'pending'
        ]);
    }
}
