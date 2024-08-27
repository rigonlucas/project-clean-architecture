<?php

namespace Core\Support\Exceptions;

use Exception;

class OutputErrorException extends Exception
{
    public function __construct(
        string $message,
        int $code,
        Exception $previous = null,
        private readonly ?array $errors = []
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
