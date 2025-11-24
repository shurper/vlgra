<?php

declare(strict_types=1);

namespace App\UseCase;

use App\Repository\OrderRepositoryInterface;
use App\Repository\BuyerRepositoryInterface;
use App\ShippingServiceInterface;

class ShipOrder
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders,
        private readonly BuyerRepositoryInterface $buyers,
        private readonly ShippingServiceInterface $shippingService,
    ) {
    }

    public function execute(int $orderId, int $buyerId): string
    {
        $order = $this->orders->get($orderId);
        $buyer = $this->buyers->get($buyerId);

        $order->load();

        return $this->shippingService->ship($order, $buyer);
    }
}
