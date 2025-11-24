<?php

namespace App\Data;

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
     * @return array<int|string, mixed>
     */
    protected function loadOrderData(int $id): array
    {
        $path = rtrim($this->mockDir, '/\\') . "/order.$id.json";
        if (!is_readable($path)) {
            throw new RuntimeException("Order mock file not found: $path");
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            throw new RuntimeException("Failed to read order mock: $path");
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            throw new RuntimeException("Invalid JSON in order mock: $path");
        }

        return $data;
    }
}
