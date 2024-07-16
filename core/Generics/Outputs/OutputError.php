<?php

namespace Core\Generics\Outputs;

readonly class OutputError implements GenericOutput
{

    public function __construct(public OutputStatus $status, public string $message)
    {
    }

    public function getMessages(): string
    {
        return $this->message;
    }

    public function getStatus(): OutputStatus
    {
        return $this->status;
    }
}
