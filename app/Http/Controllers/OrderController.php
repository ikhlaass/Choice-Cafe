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
     * Store the order
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

        // Generate transaction code
        $transactionCode = 'JV' . rand(1000, 9999);

        // Store order info in session
        Session::put('last_order', [
            'transaction_code' => $transactionCode,
            'total_amount' => $total,
            'payment_method' => 'qris', // Fixed to QRIS only
            'items' => $items,
            'status' => 'cooking',
            'estimated_time' => '15 Min'
        ]);

        // Clear cart
        Session::forget('cart');

        return redirect()->route('orders.success')
            ->with('success', 'Order placed successfully!');
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