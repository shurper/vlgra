<?php

declare(strict_types=1);

namespace App\Data;

use ArrayAccess;

/**
 * @extends ArrayAccess<string, mixed>
 */
interface BuyerInterface extends ArrayAccess
{
    public function getCountryCode(): string;

    public function getEmail(): ?string;

    public function getPhone(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
