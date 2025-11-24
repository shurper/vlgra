<?php

declare(strict_types=1);

namespace App\Presentation\Bootstrap;

use App\Presentation\Controller\DemoController;
use App\Repository\MockBuyerRepository;
use App\Repository\MockOrderRepository;
use App\Service\FbaPayloadBuilder;
use App\Service\FbaShippingService;
use App\Service\MockSpApiClient;
use App\Service\StubFbaClient;
use App\UseCase\ShipOrder;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class DemoBootstrap
{
    public function __construct(private readonly string $rootDir)
    {
    }

    public function controller(): DemoController
    {
        $loader = new FilesystemLoader($this->rootDir . '/templates');
        $twig = new Environment($loader, [
            'cache' => false,
            'autoescape' => 'html',
        ]);

        $orders = new MockOrderRepository($this->rootDir . '/mock');
        $buyers = new MockBuyerRepository($this->rootDir . '/mock');
        $client = getenv('USE_SPAPI_MOCK') ? new MockSpApiClient() : new StubFbaClient();
        $shipping = new FbaShippingService(new FbaPayloadBuilder(), $client);
        $useCase = new ShipOrder($orders, $buyers, $shipping);

        return new DemoController($useCase, $orders, $buyers, $twig);
    }
}
