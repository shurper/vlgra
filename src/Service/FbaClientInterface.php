<?php

declare(strict_types=1);

namespace App\Service;

interface FbaClientInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function send(array $payload): string;
}
