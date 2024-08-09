<?php

namespace Core\Presentation\Errors;

use Core\Generics\Presenters\ToArrayPresenterInterface;

readonly class ErrorPresenter implements ToArrayPresenterInterface
{

    public function __construct(
        private string $message,
        public ?array $errors = [],
        public ?array $trace = [],
        private bool $isDevelopementMode = false
    ) {
    }

    public function toArray(): array
    {
        return [
            ...[
                'message' => $this->message,
                'errors' => $this->errors
            ],
            ...($this->isDevelopementMode ? ['trace' => $this->trace] : [])
        ];
    }
}
