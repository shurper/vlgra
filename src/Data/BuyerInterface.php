<?php

declare(strict_types=1);

namespace App\Data;

interface BuyerInterface
{
    public function getCountryCode(): string;

    public function getEmail(): ?string;

    public function getPhone(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
