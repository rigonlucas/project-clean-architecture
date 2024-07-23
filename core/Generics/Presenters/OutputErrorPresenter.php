<?php

namespace Core\Generics\Presenters;

use Core\Generics\Outputs\OutputError;

readonly class OutputErrorPresenter implements GenericPresenter
{

    public function __construct(
        private OutputError $output,
        public ?array $trace = [],
        private bool $isDevelopementMode = false
    ) {
    }

    public function toArray(): array
    {
        return [
            ...[
                'message' => $this->output->status->message,
                'error' => $this->output->status->statusCode
            ],
            ...($this->isDevelopementMode ? ['trace' => $this->trace] : [])
        ];
    }
}
