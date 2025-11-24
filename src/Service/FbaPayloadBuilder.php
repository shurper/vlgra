<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\AbstractOrder;
use App\Data\BuyerInterface;
use RuntimeException;

class FbaPayloadBuilder
{
    /**
     * @return array<string, mixed>
     */
    public function build(AbstractOrder $order, BuyerInterface $buyer): array
    {
        $order->load();
        $orderData = $order->getData();

        $requiredOrderKeys = ['shipping_country', 'shipping_zip', 'shipping_adress', 'products'];
        foreach ($requiredOrderKeys as $key) {
            if (!array_key_exists($key, $orderData)) {
                throw new RuntimeException("Missing required order field: {$key}");
            }
        }

        if (!is_array($orderData['products']) || count($orderData['products']) === 0) {
            throw new RuntimeException('Order products list is empty.');
        }

        return [
            'order_id' => $order->getOrderId(),
            'shipping' => [
                'country' => $orderData['shipping_country'],
                'postal_code' => $orderData['shipping_zip'],
                'address' => $orderData['shipping_adress'],
            ],
            'buyer' => [
                'country_code' => $buyer->getCountryCode(),
                'email' => $buyer->getEmail(),
                'phone' => $buyer->getPhone(),
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
}
