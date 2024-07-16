<?php

namespace Core\Generics\Outputs;

class OutputStatus
{
    public function __construct(
        public readonly int $statusCode,
        public readonly string $message
    ) {
    }
}
