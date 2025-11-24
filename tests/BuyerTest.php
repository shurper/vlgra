<?php

declare(strict_types=1);

namespace Tests;

use App\Data\Buyer;
use App\Exception\DomainException;
use PHPUnit\Framework\TestCase;

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
        $this->expectException(DomainException::class);
        new Buyer(['email' => 'x@test.com']);
    }
}
