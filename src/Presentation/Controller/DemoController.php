<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Presentation\View\DemoViewModel;
use App\Repository\BuyerRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\UseCase\ShipOrder;
use Twig\Environment;
use App\Exception\DomainException;
use Throwable;

class DemoController
{
    public function __construct(
        private readonly ShipOrder $shipOrder,
        private readonly OrderRepositoryInterface $orders,
        private readonly BuyerRepositoryInterface $buyers,
        private readonly Environment $twig,
    ) {
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $body
     */
    public function handle(string $method, array $query, array $body): string
    {
        $orderId = isset($query['order_id']) ? (int) $query['order_id'] : 16400;
        $buyerId = isset($query['buyer_id']) ? (int) $query['buyer_id'] : 29664;

        $tracking = null;
        $error = null;
        $orderData = null;
        $buyerData = null;

        if (strtoupper($method) === 'POST') {
            $orderId = isset($body['order_id']) ? (int) $body['order_id'] : $orderId;
            $buyerId = isset($body['buyer_id']) ? (int) $body['buyer_id'] : $buyerId;

            try {
                $this->assertIds($orderId, $buyerId);

                $tracking = $this->shipOrder->execute($orderId, $buyerId);
                $order = $this->orders->get($orderId);
                $order->load();
                $orderData = $order->getData();
                $buyerData = $this->buyers->get($buyerId)->toArray();
            } catch (Throwable $e) {
                $error = ($e instanceof DomainException)
                    ? $e->getUserMessage()
                    : 'Unexpected error. Please try again.';
                error_log($e->getMessage());
            }
        }

        $viewModel = new DemoViewModel($orderId, $buyerId, $tracking, $error, $orderData, $buyerData);

        return $this->twig->render('demo.html.twig', ['view' => $viewModel]);
    }

    private function assertIds(int $orderId, int $buyerId): void
    {
        if ($orderId <= 0 || $buyerId <= 0) {
            throw new DomainException('Invalid ids provided.', 'Please provide valid order and buyer ids.');
        }
    }
}
