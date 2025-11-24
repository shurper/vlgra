<?php

namespace App\Data;

abstract class AbstractOrder
{
    private int $id;
    /** @var array<int|string, mixed>|null */
    public ?array $data = null;

    /**
     * @return array<int|string, mixed>
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
    }
}
