<?php

namespace App\Exceptions;

class OrderValidationException extends \RuntimeException
{
    public function __construct(
        string $userMessage,
        private string $errorCode,
        private array $context = [],
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($userMessage, $code, $previous);
    }
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
