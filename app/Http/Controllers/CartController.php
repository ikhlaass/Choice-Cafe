<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Add item to cart
     * UPDATED: Tambah support AJAX
     */
    public function add(Request $request)
    {
        $menuId = $request->input('menu_id');
        $quantity = $request->input('quantity', 1);
        $price = $request->input('price');

        $cart = Session::get('cart', []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] += $quantity;
        } else {
            $cart[$menuId] = [
                'name' => $request->input('name'),
                'price' => $price,
                'quantity' => $quantity,
                'image' => $request->input('image', ''),
                'notes' => '',
            ];
        }

        Session::put('cart', $cart);

        // ✅ Cek apakah request dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart!',
                'cart_summary' => self::getCartSummary()
            ]);
        }

        // Kalau bukan AJAX, tetap redirect seperti biasa
        return redirect()->back()->with('success', 'Item added to cart!');
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('checkout')->with('success', 'Item removed!');
    }

    /**
     * Update item notes (AJAX)
     */
    public function updateNotes(Request $request, $id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['notes'] = $request->input('notes', '');
            Session::put('cart', $cart);
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    /**
     * Update item quantity
     * UPDATED: Tambah support AJAX
     */
    public function updateQuantity(Request $request)
    {
        $id = $request->input('id');
        $delta = (int) $request->input('delta', 0);
        
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $delta;
            
            // Remove item if quantity <= 0
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
            
            Session::put('cart', $cart);
        }

        // ✅ Cek apakah request dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_summary' => self::getCartSummary()
            ]);
        }
        
        return redirect()->route('checkout');
    }

    /**
     * Get cart summary for display
     */
    public static function getCartSummary()
    {
        $cart = Session::get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        
        return [
            'count' => $count,
            'total' => $total
        ];
    }

    /**
     * Calculate cart total
     */
    public static function calculateTotal()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
    }
}