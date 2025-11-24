<?php

declare(strict_types=1);

namespace App\Data;

use App\Exception\DomainException;
use RuntimeException;

class Order extends AbstractOrder
{
    private string $mockDir;

    public function __construct(int $id, ?string $mockDir = null)
    {
        parent::__construct($id);
        $this->mockDir = $mockDir ?? __DIR__ . '/../../mock';
    }

    /**
     * @return array<string, mixed>
     */
    protected function loadOrderData(int $id): array
    {
        $path = rtrim($this->mockDir, '/\\') . "/order.$id.json";
        if (!is_readable($path)) {
            throw new DomainException(
                "Order mock file not found: $path",
                sprintf('Order #%d was not found.', $id)
            );
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            throw new DomainException(
                "Failed to read order mock: $path",
                'Order cannot be loaded right now.'
            );
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            throw new DomainException(
                "Invalid JSON in order mock: $path",
                'Order data is corrupted.'
            );
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function validateData(array $data): void
    {
        $requiredOrderKeys = ['shipping_country', 'shipping_zip', 'shipping_adress', 'products'];
        foreach ($requiredOrderKeys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new DomainException(
                    "Missing required order field: {$key}",
                    'Order data is incomplete.'
                );
            }
        }

        if (!is_array($data['products']) || count($data['products']) === 0) {
            throw new DomainException(
                'Order products list is empty.',
                'Order has no items to ship.'
            );
        }
    }

    public function getShippingCountry(): string
    {
        $this->assertLoaded();

        return (string) $this->getData()['shipping_country'];
    }

    public function getShippingZip(): string
    {
        $this->assertLoaded();

        return (string) $this->getData()['shipping_zip'];
    }

    public function getShippingAddress(): string
    {
        $this->assertLoaded();

        return (string) $this->getData()['shipping_adress'];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getProducts(): array
    {
        $this->assertLoaded();

        /** @var array<int, array<string, mixed>> $products */
        $products = $this->getData()['products'];

        return $products;
    }

    private function assertLoaded(): void
    {
        $this->getData(); // will throw if not loaded
    }
}
