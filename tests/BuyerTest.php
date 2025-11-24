<?php

declare(strict_types=1);

namespace Tests;

use App\Data\Buyer;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class BuyerTest extends TestCase
{
    public function testFromMockLoadsData(): void
    {
        $buyer = Buyer::fromMock(29664, __DIR__ . '/../mock');

        $this->assertSame('US', $buyer->getCountryCode());
        $this->assertSame('buyer@test.com', $buyer->getEmail());
        $this->assertSame('123 456 7890', $buyer->getPhone());
    }

    public function testGetCountryCodeThrowsWhenMissing(): void
    {
        $buyer = new Buyer(['email' => 'x@test.com']);

        $this->expectException(RuntimeException::class);
        $buyer->getCountryCode();
    }
}
