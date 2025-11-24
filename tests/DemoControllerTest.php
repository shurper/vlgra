<?php

declare(strict_types=1);

namespace Tests;

use App\Presentation\Controller\DemoController;
use App\Repository\MockBuyerRepository;
use App\Repository\MockOrderRepository;
use App\UseCase\ShipOrder;
use App\Service\FbaPayloadBuilder;
use App\Service\FbaShippingService;
use App\Service\StubFbaClient;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use PHPUnit\Framework\TestCase;

class DemoControllerTest extends TestCase
{
    private function controller(): DemoController
    {
        $twig = new Environment(new ArrayLoader([
            'demo.html.twig' => '{{ view.tracking|default("") }}|{{ view.error|default("") }}'
        ]));

        $orders = new MockOrderRepository(__DIR__ . '/../mock');
        $buyers = new MockBuyerRepository(__DIR__ . '/../mock');
        $shipping = new FbaShippingService(new FbaPayloadBuilder(), new StubFbaClient());
        $useCase = new ShipOrder($orders, $buyers, $shipping);

        return new DemoController($useCase, $orders, $buyers, $twig);
    }

    public function testHandleReturnsTrackingOnSuccess(): void
    {
        $controller = $this->controller();

        $output = $controller->handle('POST', [], ['order_id' => 16400, 'buyer_id' => 29664]);

        $this->assertStringContainsString('FBA-16400-', $output);
        $this->assertStringContainsString('|', $output); // separator present
    }

    public function testHandleReturnsFriendlyErrorOnInvalidIds(): void
    {
        $controller = $this->controller();

        $output = $controller->handle('POST', [], ['order_id' => 0, 'buyer_id' => -1]);

        $this->assertStringContainsString('Please provide valid order and buyer ids.', $output);
    }
}
