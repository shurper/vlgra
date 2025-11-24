<?php

declare(strict_types=1);

namespace App\Service;

class StubFbaClient implements FbaClientInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function send(array $payload): string
    {
        $hash = substr(hash('crc32b', json_encode($payload) ?: ''), 0, 8);

        return 'FBA-' . ($payload['order_id'] ?? 'UNKNOWN') . '-' . $hash;
    }
}
