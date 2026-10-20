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
        $allMenus = [
            [
                'id' => 1,
                'name' => 'Nasi Goreng Spesial Choice',
                'description' => 'Nasi goreng harum rempah dengan ayam suwir gurih, telur mata sapi & kerupuk udang renyah.',
                'price' => 25000,
                'image' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=600&auto=format&fit=crop&q=80',
                'category' => 'Main Menu',
                'rating' => '4.9',
                'prep_time' => '10-15 mnt',
                'badge' => 'Chef Choice'
            ],
            [
                'id' => 2,
                'name' => 'Oseng Daging Mercon',
                'description' => 'Potongan daging sapi empuk dimasak pedas mercon cabai rawit merah segar yang nendang.',
                'price' => 32000,
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&auto=format&fit=crop&q=80',
                'category' => 'Main Menu',
                'rating' => '4.8',
                'prep_time' => '12-15 mnt',
                'badge' => 'Super Pedas'
            ],
            [
                'id' => 3,
                'name' => 'Rawon Daging Kuah Bening',
                'description' => 'Rawon daging khas Jawa Timur berkuah rempah kluwek gurih, disajikan dengan tauge & sambal.',
                'price' => 30000,
                'image' => 'https://images.unsplash.com/photo-1547928576-a4a33237cbc3?w=600&auto=format&fit=crop&q=80',
                'category' => 'Main Menu',
                'rating' => '4.9',
                'prep_time' => '10 mnt',
                'badge' => 'Favorite'
            ],
            [
                'id' => 4,
                'name' => 'Nasi Goreng Kampung',
                'description' => 'Nasi goreng bumbu tradisional resep tempo dulu dengan irisan ayam kampung & acar segar.',
                'price' => 22000,
                'image' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&auto=format&fit=crop&q=80',
                'category' => 'Main Menu',
                'rating' => '4.7',
                'prep_time' => '10 mnt',
                'badge' => null
            ],
            [
                'id' => 5,
                'name' => 'Gudeg Komplit Jogja',
                'description' => 'Nasi gudeg nangka manis gurih dengan ayam suwir, telur pindang cokelat & sambal krecek.',
                'price' => 28000,
                'image' => 'https://images.unsplash.com/photo-1565299585323-38d6b0865b47?w=600&auto=format&fit=crop&q=80',
                'category' => 'Main Menu',
                'rating' => '4.8',
                'prep_time' => '8 mnt',
                'badge' => 'Otentik'
            ],
            [
                'id' => 6,
                'name' => 'Nasi Ayam Penyet Sambal Bawang',
                'description' => 'Ayam goreng keemasan dipenyet dengan sambal bawang pedas gurih, tahu, tempe & lalapan.',
                'price' => 26000,
                'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=600&auto=format&fit=crop&q=80',
                'category' => 'Main Menu',
                'rating' => '4.9',
                'prep_time' => '12 mnt',
                'badge' => 'Best Seller'
            ],
            [
                'id' => 7,
                'name' => 'Sate Ayam Madura (8 Tusuk)',
                'description' => 'Daging ayam bakar empuk beraroma arang dengan siraman bumbu kacang kental & kecap manis.',
                'price' => 24000,
                'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&auto=format&fit=crop&q=80',
                'category' => 'Appetizer',
                'rating' => '4.8',
                'prep_time' => '15 mnt',
                'badge' => null
            ],
            [
                'id' => 8,
                'name' => 'Fresh Ice Lemon Tea',
                'description' => 'Perpaduan teh melati segar dingin dengan perasan buah lemon asli dan sensasi daun mint.',
                'price' => 12000,
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&auto=format&fit=crop&q=80',
                'category' => 'Drinks',
                'rating' => '4.9',
                'prep_time' => '5 mnt',
                'badge' => 'Segar'
            ],
            [
                'id' => 9,
                'name' => 'Es Cendol Nangka Santan Murni',
                'description' => 'Cendol kenyal pandan wangi berpadu santan kelapa gurih, gula aren asli & irisan nangka.',
                'price' => 15000,
                'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600&auto=format&fit=crop&q=80',
                'category' => 'Desserts',
                'rating' => '4.9',
                'prep_time' => '5 mnt',
                'badge' => 'Sweet'
            ],
        ];

        // Filter by category
        $activeCategory = $request->get('category', 'All');
        if ($activeCategory && $activeCategory !== 'All') {
            $menus = array_filter($allMenus, fn($menu) => $menu['category'] === $activeCategory);
            $menus = array_values($menus);
        } else {
            $activeCategory = 'All';
            $menus = $allMenus;
        }

        // Categories list
        $rawCategories = array_values(array_unique(array_column($allMenus, 'category')));
        $categories = array_merge(['All'], $rawCategories);

        // Featured items for top recommendations
        $featuredMenus = array_slice($allMenus, 0, 3);

        // Get cart summary
        $cartSummary = CartController::getCartSummary();

        // Get table number from session (if scanned QR)
        $tableNumber = session('table_number', '01');

        return view('menus.index', compact('menus', 'categories', 'activeCategory', 'cartSummary', 'tableNumber', 'featuredMenus'));
    }
}