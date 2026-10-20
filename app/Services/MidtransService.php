<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    protected $serverKey;
    protected $isProduction;

    public function __construct()
    {
        // Set Midtrans configuration
        $this->serverKey = config('services.midtrans.server_key');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);

        if (!empty($this->serverKey)) {
            Config::$serverKey = $this->serverKey;
            Config::$isProduction = $this->isProduction;
            Config::$isSanitized = true;
            Config::$is3ds = true;
        }
    }

    /**
     * Create Snap payment token
     *
     * @param array $orderData
     * @return array
     */
    public function createPayment($orderData)
    {
        // If Midtrans Server Key is empty, fallback gracefully to simulation/demo mode
        if (empty($this->serverKey)) {
            \Log::info('Midtrans Server Key is not configured. Falling back to Demo mode for order: ' . $orderData['transaction_code']);

            return [
                'success' => true,
                'is_demo' => true,
                'snap_token' => null,
                'transaction_code' => $orderData['transaction_code'],
            ];
        }

        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $orderData['transaction_code'],
                    'gross_amount' => (int) $orderData['amount'],
                ],
                'customer_details' => [
                    'first_name' => 'Table ' . $orderData['table_number'],
                    'email' => 'customer@choicecafe.com',
                    'phone' => '08123456789',
                ],
                'item_details' => $this->formatItems($orderData['items']),
                // Let Midtrans use all available payment methods from dashboard settings
            ];

            $snapToken = Snap::getSnapToken($params);

            return [
                'success' => true,
                'is_demo' => false,
                'snap_token' => $snapToken,
                'transaction_code' => $orderData['transaction_code'],
            ];

        } catch (\Exception $e) {
            \Log::error('Midtrans Payment Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction status
     *
     * @param string $orderId
     * @return array
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);

            return [
                'success' => true,
                'transaction_status' => $status->transaction_status,
                'fraud_status' => $status->fraud_status ?? null,
                'order_id' => $status->order_id,
            ];

        } catch (\Exception $e) {
            \Log::error('Midtrans Status Check Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format items for Midtrans
     *
     * @param array $cartItems
     * @return array
     */
    protected function formatItems($cartItems)
    {
        $items = [];

        foreach ($cartItems as $item) {
            $items[] = [
                'id' => $item['id'] ?? uniqid(),
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => $item['name'],
            ];
        }

        return $items;
    }
}
