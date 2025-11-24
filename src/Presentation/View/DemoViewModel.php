<?php

declare(strict_types=1);

namespace App\Presentation\View;

class DemoViewModel
{
    /**
     * @param array<string, mixed>|null $orderData
     * @param array<string, mixed>|null $buyerData
     */
    public function __construct(
        public readonly int $orderId,
        public readonly int $buyerId,
        public readonly ?string $tracking,
        public readonly ?string $error,
        public readonly ?array $orderData,
        public readonly ?array $buyerData,
    ) {
    }
}
