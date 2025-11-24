<?php

namespace App\Data;

use RuntimeException;

class Buyer implements BuyerInterface
{
    /** @var array<int|string, mixed> */
    private array $data;

    /**
     * @param array<int|string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fromMock(int $id, ?string $mockDir = null): self
    {
        $mockDir = $mockDir ?? __DIR__ . '/../../mock';
        $path = rtrim($mockDir, '/\\') . "/buyer.$id.json";
        if (!is_readable($path)) {
            throw new RuntimeException("Buyer mock file not found: $path");
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            throw new RuntimeException("Failed to read buyer mock: $path");
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            throw new RuntimeException("Invalid JSON in buyer mock: $path");
        }

        return new self($data);
    }

    // ArrayAccess
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->data);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }
}
