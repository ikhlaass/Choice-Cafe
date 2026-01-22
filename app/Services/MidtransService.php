<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create Snap payment token
     *
     * @param array $orderData
     * @return array
     */
    public function createPayment($orderData)
    {
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
