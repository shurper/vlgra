<?php

declare(strict_types=1);

namespace Tests;

use App\Data\Buyer;
use App\Data\Order;
use App\Service\FbaPayloadBuilder;
use App\Service\MockSpApiClient;
use PHPUnit\Framework\TestCase;

class MockSpApiClientTest extends TestCase
{
    public function testSendBuildsRequestAndReturnsTracking(): void
    {
        $order = new Order(16400, __DIR__ . '/../mock');
        $order->load();
        $buyer = Buyer::fromMock(29664, __DIR__ . '/../mock');

        $builder = new FbaPayloadBuilder();
        $request = $builder->build($order, $buyer);

        $client = new MockSpApiClient();
        $tracking = $client->send($request);

        $this->assertStringStartsWith('MOCK-SPAPI-16400-', $tracking);
        $blueprint = $client->getLastRequest();
        $this->assertNotNull($blueprint);
        $this->assertSame('POST', $blueprint['method']);
        $this->assertSame('/fba/outbound/v20200701/fulfillmentOrders', $blueprint['path']);
    }
}
