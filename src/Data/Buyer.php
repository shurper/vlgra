<?php

declare(strict_types=1);

namespace App\Data;

use App\Exception\DomainException;
use RuntimeException;

class Buyer implements BuyerInterface
{
    /** @var array<string, mixed> */
    private array $data;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->validate();
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

    public function getCountryCode(): string
    {
        $code = $this->data['country_code'] ?? null;
        if (!is_string($code) || $code === '') {
            throw new DomainException('Buyer country_code is required.');
        }

        return $code;
    }

    public function getEmail(): ?string
    {
        $email = $this->data['email'] ?? null;

        return is_string($email) && $email !== '' ? $email : null;
    }

    public function getPhone(): ?string
    {
        $phone = $this->data['phone'] ?? null;

        return is_string($phone) && $phone !== '' ? $phone : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->data;
    }

    private function validate(): void
    {
        $this->getCountryCode();
    }
}
