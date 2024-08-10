<?php

namespace Core\Presentation\Http\Errors;

use Core\Generics\Presenters\ToArrayPresenter;

readonly class ErrorPresenter implements ToArrayPresenter
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

    public function withDataAttribute(): self
    {
        return $this;
    }
}
