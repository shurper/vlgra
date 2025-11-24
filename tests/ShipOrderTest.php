<?php

declare(strict_types=1);

namespace Tests;

use App\Repository\MockBuyerRepository;
use App\Repository\MockOrderRepository;
use App\Service\FbaPayloadBuilder;
use App\Service\FbaShippingService;
use App\Service\StubFbaClient;
use App\UseCase\ShipOrder;
use PHPUnit\Framework\TestCase;

class ShipOrderTest extends TestCase
{
    public function testExecuteReturnsTrackingNumber(): void
    {
        $useCase = new ShipOrder(
            new MockOrderRepository(__DIR__ . '/../mock'),
            new MockBuyerRepository(__DIR__ . '/../mock'),
            new FbaShippingService(new FbaPayloadBuilder(), new StubFbaClient())
        );

        $tracking = $useCase->execute(16400, 29664);

        $this->assertStringStartsWith('FBA-16400-', $tracking);
    }
}
