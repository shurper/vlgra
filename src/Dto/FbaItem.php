<?php

declare(strict_types=1);

namespace App\Dto;

class FbaItem
{
    public function __construct(
        public readonly string $sku,
        public readonly int $quantity,
        public readonly string $title
    ) {
    }
}
