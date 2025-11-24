<?php

declare(strict_types=1);

namespace App\Data;

use RuntimeException;

abstract class AbstractOrder
{
    private int $id;

    /** @var array<string, mixed> */
    private array $data = [];

    private bool $loaded = false;

    /**
     * @return array<string, mixed>
     */
    abstract protected function loadOrderData(int $id): array;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    abstract public function getShippingCountry(): string;

    abstract public function getShippingZip(): string;

    abstract public function getShippingAddress(): string;

    /**
     * @return array<int, array<string, mixed>>
     */
    abstract public function getProducts(): array;

    final public function getOrderId(): int
    {
        return $this->id;
    }

    final public function load(): void
    {
        $data = $this->loadOrderData($this->getOrderId());
        $this->validateData($data);
        $this->data = $data;
        $this->loaded = true;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function validateData(array $data): void
    {
        // Default no-op; concrete implementations may override.
    }

    /**
     * @return array<string, mixed>
     */
    final public function getData(): array
    {
        if (!$this->loaded) {
            throw new RuntimeException('Order data not loaded. Call load() first.');
        }

        return $this->data;
    }
}
