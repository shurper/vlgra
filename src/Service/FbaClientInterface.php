<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\FbaShipmentRequest;

interface FbaClientInterface
{
    public function send(FbaShipmentRequest $request): string;
}
