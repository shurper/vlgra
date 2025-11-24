<?php

declare(strict_types=1);

namespace Tests;

use App\Data\Order;
use App\Data\Buyer;
use App\Service\FbaPayloadBuilder;
use App\Service\FbaShippingService;
use App\Service\StubFbaClient;
use App\Domain\Shipping\ShippingServiceInterface;
use PHPUnit\Framework\TestCase;

class ShippingServiceInterfaceCompatTest extends TestCase
{
    public function testFbaShippingServiceImplementsOriginalInterface(): void
    {
        $service = new FbaShippingService(new FbaPayloadBuilder(), new StubFbaClient());
        $this->assertInstanceOf(ShippingServiceInterface::class, $service);

        $order = new Order(16400, __DIR__ . '/../mock');
        $buyer = Buyer::fromMock(29664, __DIR__ . '/../mock');
        $order->load();

        $tracking = $service->ship($order, $buyer);
        $this->assertStringStartsWith('FBA-16400-', $tracking);
    }
}
