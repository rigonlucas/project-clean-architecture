<?php

namespace Core\Generics\Outputs;

use Core\Generics\Presenters\GenericPresenter;
use Core\Generics\Presenters\OutputErrorPresenter;

readonly class OutputError implements GenericOutput
{
    public function __construct(
        public OutputStatus $status,
        public ?string $message = null,
        public ?array $trace = [],
        public bool $isDevelopementMode = false
    ) {
    }

    public function getMessages(): string
    {
        return $this->message;
    }

    public function getStatus(): OutputStatus
    {
        return $this->status;
    }

    public function getPresenter(): GenericPresenter
    {
        return new OutputErrorPresenter($this, $this->trace, $this->isDevelopementMode);
    }
}
