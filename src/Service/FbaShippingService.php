<?php

declare(strict_types=1);

namespace App\Service;

use App\Data\AbstractOrder;
use App\Data\BuyerInterface;
use App\Domain\Shipping\ShippingServiceInterface;
use RuntimeException;

class FbaShippingService implements ShippingServiceInterface
{
    public function __construct(
        private readonly FbaPayloadBuilder $payloadBuilder,
        private readonly FbaClientInterface $client,
    ) {
    }

    public function ship(AbstractOrder $order, BuyerInterface $buyer): string
    {
        $payload = $this->payloadBuilder->build($order, $buyer);

        $trackingNumber = $this->client->send($payload);
        if ($trackingNumber === '') {
            throw new RuntimeException('FBA did not return a tracking number.');
        }

        return $trackingNumber;
    }
}
