<?php

declare(strict_types=1);

namespace Tests;

use App\Data\Order;
use App\Data\Buyer;
use App\Service\FbaPayloadBuilder;
use PHPUnit\Framework\TestCase;

class FbaPayloadBuilderTest extends TestCase
{
    public function testBuildProducesPayloadWithMappedFields(): void
    {
        $order = new Order(16400, __DIR__ . '/../mock');
        $order->load();
        $buyer = Buyer::fromMock(29664, __DIR__ . '/../mock');

        $builder = new FbaPayloadBuilder();
        $request = $builder->build($order, $buyer);
        $payload = $request->toArray();

        $this->assertSame(16400, $payload['order_id']);
        $this->assertSame('US', $payload['shipping']['country']);
        $this->assertSame('buyer@test.com', $payload['buyer']['email']);
        $this->assertCount(2, $payload['items']);
        $this->assertArrayHasKey('sku', $payload['items'][0]);
        $this->assertArrayHasKey('quantity', $payload['items'][0]);
    }
}
