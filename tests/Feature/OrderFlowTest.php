<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_store_creates_order_directly_and_redirects_to_success(): void
    {
        $sessionData = [
            'table_number' => '05',
            'cart' => [
                1 => [
                    'id' => 1,
                    'name' => 'Nasi Goreng Spesial',
                    'price' => 100,
                    'quantity' => 1,
                    'notes' => 'Pedas sedang'
                ]
            ]
        ];

        $response = $this->withSession($sessionData)
            ->post('/order/store', [
                'total_amount' => 100,
            ]);

        // Expect direct redirect to orders.success
        $response->assertStatus(302);
        $response->assertRedirect(route('orders.success'));

        // Verify cart is cleared
        $this->assertFalse(session()->has('cart'));

        // Verify session contains last_order
        $this->assertTrue(session()->has('last_order'));
        $lastOrder = session('last_order');
        $this->assertEquals(100, $lastOrder['total_amount']);
        $this->assertEquals('Bayar di Kasir (Tunai)', $lastOrder['payment_method']);

        // Verify order saved in database
        $this->assertDatabaseHas('orders', [
            'total_amount' => 100,
            'customer_name' => 'Meja 05',
        ]);
    }
}
