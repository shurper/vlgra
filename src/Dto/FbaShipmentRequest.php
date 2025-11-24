<?php

declare(strict_types=1);

namespace App\Dto;

/**
 * Immutable DTO representing a shipment request to FBA.
 */
class FbaShipmentRequest
{
    /** @param FbaItem[] $items */
    public function __construct(
        public readonly int $orderId,
        public readonly FbaShippingAddress $shipping,
        public readonly FbaBuyerContact $buyer,
        public readonly array $items
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'shipping' => [
                'country' => $this->shipping->country,
                'postal_code' => $this->shipping->postalCode,
                'address' => $this->shipping->address,
            ],
            'buyer' => [
                'country_code' => $this->buyer->countryCode,
                'email' => $this->buyer->email,
                'phone' => $this->buyer->phone,
            ],
            'items' => array_map(
                static fn(FbaItem $item): array => [
                    'sku' => $item->sku,
                    'quantity' => $item->quantity,
                    'title' => $item->title,
                ],
                $this->items
            ),
        ];
    }
}
