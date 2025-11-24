<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\Buyer;
use App\Data\BuyerInterface;

class MockBuyerRepository implements BuyerRepositoryInterface
{
    public function __construct(private readonly string $mockDir)
    {
    }

    public function get(int $buyerId): BuyerInterface
    {
        return Buyer::fromMock($buyerId, $this->mockDir);
    }
}
