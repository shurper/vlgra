<?php

declare(strict_types=1);

namespace App\Dto;

class FbaShippingAddress
{
    public function __construct(
        public readonly string $country,
        public readonly string $postalCode,
        public readonly string $address
    ) {
    }
}
