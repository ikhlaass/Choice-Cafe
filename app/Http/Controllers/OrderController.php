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
     * Store the order directly (Bayar di Kasir / Tunai - Tanpa payment gateway)
     */
    public function store(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|numeric',
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Keranjang masih kosong!');
        }

        $items = array_values($cart);
        $total = $request->input('total_amount');
        $tableNumber = Session::get('table_number', '01');

        // Generate transaction code
        $transactionCode = 'JV' . date('ymdHis') . rand(100, 999);

        // Save order directly into database
        $order = \App\Models\Order::create([
            'customer_name' => 'Meja ' . str_pad($tableNumber, 2, '0', STR_PAD_LEFT),
            'customer_email' => 'meja' . str_pad($tableNumber, 2, '0', STR_PAD_LEFT) . '@choicecafe.com',
            'customer_phone' => '08123456789',
            'notes' => 'Transaksi: #' . $transactionCode . ' (Bayar di Kasir / Tunai)',
            'total_amount' => $total,
            'status' => 'pending',
            'items' => $items,
        ]);

        // Store completed order info in session for success display
        Session::put('last_order', [
            'id' => $order->id,
            'transaction_code' => $transactionCode,
            'total_amount' => $total,
            'table_number' => $tableNumber,
            'payment_method' => 'Bayar di Kasir (Tunai)',
            'items' => $items,
            'status' => 'cooking',
            'estimated_time' => '15 Menit',
            'created_at' => now(),
        ]);

        // Clear cart
        Session::forget('cart');

        // Redirect directly to success page
        return redirect()->route('orders.success')
            ->with('success', 'Pesanan berhasil dikonfirmasi!');
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