<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\FbaShipmentRequest;

class StubFbaClient implements FbaClientInterface
{
    public function send(FbaShipmentRequest $request): string
    {
        $hash = substr(hash('crc32b', json_encode($request->toArray()) ?: ''), 0, 8);

        return 'FBA-' . $request->orderId . '-' . $hash;
    }
}
