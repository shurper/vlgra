<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\Order;

class MockOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private readonly string $mockDir)
    {
    }

    public function get(int $orderId): Order
    {
        return new Order($orderId, $this->mockDir);
    }
}
