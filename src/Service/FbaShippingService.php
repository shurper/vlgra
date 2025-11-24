<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\AbstractOrder;
use App\Data\BuyerInterface;
use App\ShippingServiceInterface;
use RuntimeException;

class FbaShippingService implements ShippingServiceInterface
{
    public function ship(AbstractOrder $order, BuyerInterface $buyer): string
    {
        $order->load();

        $orderData = $order->data;
        if (!is_array($orderData)) {
            throw new RuntimeException('Order data failed to load.');
        }

        $payload = $this->buildPayload($order->getOrderId(), $orderData, $buyer);

        $trackingNumber = $this->sendToFba($payload);
        if ($trackingNumber === '') {
            throw new RuntimeException('FBA did not return a tracking number.');
        }

        return $trackingNumber;
    }

    /**
     * @param array<int|string, mixed> $orderData
     * @return array<string, mixed>
     */
    private function buildPayload(int $orderId, array $orderData, BuyerInterface $buyer): array
    {
        $requiredOrderKeys = ['shipping_country', 'shipping_zip', 'shipping_adress', 'products'];
        foreach ($requiredOrderKeys as $key) {
            if (!array_key_exists($key, $orderData)) {
                throw new RuntimeException("Missing required order field: {$key}");
            }
        }

        if (!is_array($orderData['products']) || count($orderData['products']) === 0) {
            throw new RuntimeException('Order products list is empty.');
        }

        $buyerCountry = $buyer['country_code'] ?? null;
        if (!is_string($buyerCountry) || $buyerCountry === '') {
            throw new RuntimeException('Buyer country_code is required.');
        }

        return [
            'order_id' => $orderId,
            'shipping' => [
                'country' => $orderData['shipping_country'],
                'postal_code' => $orderData['shipping_zip'],
                'address' => $orderData['shipping_adress'],
            ],
            'buyer' => [
                'country_code' => $buyerCountry,
                'email' => $buyer['email'] ?? null,
                'phone' => $buyer['phone'] ?? null,
            ],
            'items' => array_map(
                static fn(array $item): array => [
                    'sku' => $item['sku'] ?? $item['product_code'] ?? 'UNKNOWN-SKU',
                    'quantity' => (int) ($item['ammount'] ?? 1),
                    'title' => $item['title'] ?? '',
                ],
                $orderData['products']
            ),
        ];
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function sendToFba(array $payload): string
    {
        // Placeholder for real HTTP/SP-API/MWS client call.
        $hash = substr(hash('crc32b', json_encode($payload) ?: ''), 0, 8);

        return 'FBA-' . $payload['order_id'] . '-' . $hash;
    }
}
