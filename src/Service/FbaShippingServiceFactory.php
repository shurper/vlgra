<?php

declare(strict_types=1);

namespace App\Service;

class FbaShippingServiceFactory
{
    public static function create(): FbaShippingService
    {
        return new FbaShippingService(
            new FbaPayloadBuilder(),
            new StubFbaClient()
        );
    }
}
