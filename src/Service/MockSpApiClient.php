<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\FbaShipmentRequest;

/**
 * Simulates an SP-API FBA outbound call without real credentials.
 * Builds a request blueprint (method/path/headers/body) and returns a deterministic tracking number.
 */
class MockSpApiClient implements FbaClientInterface
{
    /** @var array<string, mixed>|null */
    private ?array $lastRequest = null;

    public function send(FbaShipmentRequest $request): string
    {
        $body = json_encode($request->toArray(), JSON_UNESCAPED_SLASHES);

        $this->lastRequest = [
            'method' => 'POST',
            'path' => '/fba/outbound/v20200701/fulfillmentOrders',
            'host' => 'sellingpartnerapi-na.amazon.com',
            'headers' => [
                'content-type' => 'application/json',
                'host' => 'sellingpartnerapi-na.amazon.com',
                'x-amz-access-token' => '<mocked>',
                'authorization' => 'AWS4-HMAC-SHA256 Credential=<mocked>/...',
            ],
            'body' => $body,
        ];

        $hash = substr(hash('crc32b', $body ?: ''), 0, 8);

        return 'MOCK-SPAPI-' . $request->orderId . '-' . $hash;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getLastRequest(): ?array
    {
        return $this->lastRequest;
    }
}
