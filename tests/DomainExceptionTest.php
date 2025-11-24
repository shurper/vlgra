<?php

declare(strict_types=1);

namespace Tests;

use App\Exception\DomainException;
use PHPUnit\Framework\TestCase;

class DomainExceptionTest extends TestCase
{
    public function testUserMessageDefaultsToSafeText(): void
    {
        $ex = new DomainException('Internal detail');

        $this->assertSame('Operation cannot be completed.', $ex->getUserMessage());
    }

    public function testUserMessageCanBeOverridden(): void
    {
        $ex = new DomainException('Internal', 'Friendly');

        $this->assertSame('Friendly', $ex->getUserMessage());
    }
}
