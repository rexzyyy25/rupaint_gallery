<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class MidtransService
{
    public function createCheckout(Order $order, string $method): array
    {
        $serverKey = config('services.midtrans.server_key');
        if (! $serverKey) {
            throw new RuntimeException('Konfigurasi MIDTRANS_SERVER_KEY belum diisi.');
        }

        $items = $order->items()->with('painting')->get()->map(fn ($item) => [
            'id' => (string) $item->painting_id,
            'price' => (int) $item->price,
            'quantity' => (int) $item->quantity,
            'name' => Str::limit($item->painting->title, 50, ''),
        ])->all();

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => (int) $order->total,
            ],
            'item_details' => $items,
            'customer_details' => array_filter([
                'first_name' => $order->customer_name,
                'email' => $order->user?->email,
                'phone' => $order->customer_phone,
                'shipping_address' => [
                    'first_name' => $order->customer_name,
                    'phone' => $order->customer_phone,
                    'address' => $order->customer_address,
                ],
            ], fn ($value) => $value !== null),
            'enabled_payments' => $method === 'qris'
                ? ['qris']
                : ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'],
            'callbacks' => [
                'finish' => route('orders.payment', $order),
            ],
        ];

        $baseUrl = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->post($baseUrl.'/snap/v1/transactions', $payload);

        if (! $response->successful() || ! $response->json('token') || ! $response->json('redirect_url')) {
            throw new RuntimeException('Midtrans menolak pembuatan checkout: '.$response->body());
        }

        return $response->json();
    }

    public function isValidNotification(array $notification): bool
    {
        $serverKey = config('services.midtrans.server_key');
        if (! $serverKey) {
            return false;
        }

        $expected = hash('sha512',
            ($notification['order_id'] ?? '').
            ($notification['status_code'] ?? '').
            ($notification['gross_amount'] ?? '').
            $serverKey
        );

        return isset($notification['signature_key'])
            && hash_equals($expected, (string) $notification['signature_key']);
    }
}
