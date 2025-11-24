<?php

declare(strict_types=1);

namespace App\Dto;

class FbaBuyerContact
{
    public function __construct(
        public readonly string $countryCode,
        public readonly ?string $email,
        public readonly ?string $phone
    ) {
    }
}
