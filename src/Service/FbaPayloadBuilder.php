<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\AbstractOrder;
use App\Data\BuyerInterface;
use App\Dto\FbaBuyerContact;
use App\Dto\FbaItem;
use App\Dto\FbaShipmentRequest;
use App\Dto\FbaShippingAddress;

class FbaPayloadBuilder
{
    public function build(AbstractOrder $order, BuyerInterface $buyer): FbaShipmentRequest
    {
        $items = array_map(
            static fn(array $item): FbaItem => new FbaItem(
                $item['sku'] ?? $item['product_code'] ?? 'UNKNOWN-SKU',
                (int) ($item['ammount'] ?? 1),
                $item['title'] ?? ''
            ),
            $order->getProducts()
        );

        return new FbaShipmentRequest(
            $order->getOrderId(),
            new FbaShippingAddress(
                $order->getShippingCountry(),
                $order->getShippingZip(),
                $order->getShippingAddress()
            ),
            new FbaBuyerContact(
                $buyer->getCountryCode(),
                $buyer->getEmail(),
                $buyer->getPhone()
            ),
            $items
        );
    }
}
