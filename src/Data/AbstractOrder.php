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

    final public function getOrderId(): int
    {
        return $this->id;
    }

    final public function load(): void
    {
        $this->data = $this->loadOrderData($this->getOrderId());
        $this->loaded = true;
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
