<?php

declare(strict_types=1);

namespace App\Repository;

use App\Data\BuyerInterface;

interface BuyerRepositoryInterface
{
    public function get(int $buyerId): BuyerInterface;
}
