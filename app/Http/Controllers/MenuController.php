<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CartController;

class MenuController extends Controller
{
    /**
     * Display menu list with category filter
     */
    public function index(Request $request)
    {
        // Menu data (CHEAP PRICES FOR TESTING PAYMENT)
        $allMenus = [
            ['id' => 1, 'name' => 'Nasi Goreng Spesial', 'description' => 'Nasi goreng dengan topping ayam dan telur', 'price' => 100, 'image' => 'https://picsum.photos/300/200?random=1', 'category' => 'Promo'],
            ['id' => 2, 'name' => 'Oseng Mercon', 'description' => 'Nasi oseng pedas mercon dengan sambal', 'price' => 200, 'image' => 'https://picsum.photos/300/200?random=2', 'category' => 'Main Menu'],
            ['id' => 3, 'name' => 'Rawon Bening Tot', 'description' => 'Rawon bening dengan kerupuk dan telur', 'price' => 300, 'image' => 'https://picsum.photos/300/200?random=3', 'category' => 'Main Menu'],
            ['id' => 4, 'name' => 'Nasi Goreng Kampung', 'description' => 'Nasi goreng, telur, ayam', 'price' => 150, 'image' => 'https://picsum.photos/300/200?random=4', 'category' => 'Main Menu'],
            ['id' => 5, 'name' => 'Gudeg Jogja', 'description' => 'Nasi, telur, jogja tempe tahu', 'price' => 400, 'image' => 'https://picsum.photos/300/200?random=5', 'category' => 'Main Menu'],
            ['id' => 6, 'name' => 'Nasi Putih Pilah', 'description' => 'Nasi dengan lauk pilihan', 'price' => 100, 'image' => 'https://picsum.photos/300/200?random=6', 'category' => 'Main Menu'],
            ['id' => 7, 'name' => 'Satay Ayam', 'description' => 'Sate ayam bumbu kacang', 'price' => 250, 'image' => 'https://picsum.photos/300/200?random=7', 'category' => 'Appetizer'],
            ['id' => 8, 'name' => 'Ice Lemon Tea', 'description' => 'Lemon tea segar dingin', 'price' => 100, 'image' => 'https://picsum.photos/300/200?random=8', 'category' => 'Drinks'],
            ['id' => 9, 'name' => 'Es Cendol', 'description' => 'Cendol santan manis', 'price' => 500, 'image' => 'https://picsum.photos/300/200?random=9', 'category' => 'Desserts'],
        ];

        // Filter by category
        $activeCategory = $request->get('category', 'Main Menu');
        $menus = array_filter($allMenus, fn($menu) => $menu['category'] === $activeCategory);
        $menus = array_values($menus);

        // Get unique categories
        $categories = array_unique(array_column($allMenus, 'category'));

        // Get cart summary
        $cartSummary = CartController::getCartSummary();

        // Get table number from session (if scanned QR)
        $tableNumber = session('table_number');

        return view('menus.index', compact('menus', 'categories', 'activeCategory', 'cartSummary', 'tableNumber'));
    }
}