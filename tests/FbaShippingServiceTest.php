<?php

declare(strict_types=1);

namespace Tests;

use App\Data\AbstractOrder;
use App\Data\Buyer;
use App\Data\Order;
use App\Service\FbaPayloadBuilder;
use App\Service\FbaShippingService;
use App\Service\StubFbaClient;
use App\Dto\FbaShipmentRequest;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class FbaShippingServiceTest extends TestCase
{
    public function testShipReturnsTrackingNumber(): void
    {
        $service = new FbaShippingService(new FbaPayloadBuilder(), new StubFbaClient());
        $order = new Order(16400, __DIR__ . '/../mock');
        $buyer = Buyer::fromMock(29664, __DIR__ . '/../mock');

        $order->load();
        $tracking = $service->ship($order, $buyer);

        $this->assertStringStartsWith('FBA-16400-', $tracking);
    }

    public function testShipThrowsOnMissingOrderFields(): void
    {
        $service = new FbaShippingService(new FbaPayloadBuilder(), new StubFbaClient());
        $order = new class (1) extends Order {
            protected function loadOrderData(int $id): array
            {
                return [
                    'shipping_zip' => '00000',
                    // missing shipping_country
                    'shipping_adress' => '123 Test',
                    'products' => [['sku' => 'SKU', 'ammount' => 1]],
                ];
            }
        };
        $buyer = new Buyer(['country_code' => 'US']);

        $this->expectException(RuntimeException::class);
        $order->load();
        $service->ship($order, $buyer);
    }

    public function testShipThrowsOnEmptyProducts(): void
    {
        $service = new FbaShippingService(new FbaPayloadBuilder(), new StubFbaClient());
        $order = new class (1) extends Order {
            protected function loadOrderData(int $id): array
            {
                return [
                    'shipping_country' => 'US',
                    'shipping_zip' => '00000',
                    'shipping_adress' => '123 Test',
                    'products' => [],
                ];
            }
        };
        $buyer = new Buyer(['country_code' => 'US']);

        $this->expectException(RuntimeException::class);
        $order->load();
        $service->ship($order, $buyer);
    }
}
