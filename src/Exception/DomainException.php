<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class DomainException extends RuntimeException
{
    private string $userMessage;

    public function __construct(
        string $message,
        ?string $userMessage = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->userMessage = $userMessage ?? 'Operation cannot be completed.';
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}
