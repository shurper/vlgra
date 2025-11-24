<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\Order;

interface OrderRepositoryInterface
{
    public function get(int $orderId): Order;
}
