<?php

declare(strict_types=1);

namespace Tests;

use App\Data\AbstractOrder;
use App\Data\Order;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class OrderTest extends TestCase
{
    public function testGetDataThrowsWhenNotLoaded(): void
    {
        $order = new class (123) extends AbstractOrder {
            protected function loadOrderData(int $id): array
            {
                return ['id' => $id];
            }

            public function getShippingCountry(): string
            {
                return 'US';
            }

            public function getShippingZip(): string
            {
                return '00000';
            }

            public function getShippingAddress(): string
            {
                return '123 Test';
            }

            public function getProducts(): array
            {
                return [];
            }
        };

        $this->expectException(RuntimeException::class);
        $order->getData();
    }

    public function testLoadReadsMockData(): void
    {
        $order = new Order(16400, __DIR__ . '/../mock');
        $order->load();

        $data = $order->getData();
        $this->assertSame('16400', $data['order_id']);
        $this->assertSame('US', $data['shipping_country']);
        $this->assertIsArray($data['products']);
        $this->assertNotEmpty($data['products']);
    }

    public function testLoadThrowsOnMissingFile(): void
    {
        $order = new Order(99999, __DIR__ . '/../mock');

        $this->expectException(RuntimeException::class);
        $order->load();
    }
}
