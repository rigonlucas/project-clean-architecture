<?php

namespace Core\Modules\User\Update\Presenter;

use Core\Generics\Presenters\GenericPresenter;
use Core\Modules\User\Update\Output\UpdateUserOutputError;

readonly class UpdateUserErrorPresenter implements GenericPresenter
{

    public function __construct(
        private UpdateUserOutputError $output,
        public ?array $trace = [],
        private bool $isDevelopementMode = false
    ) {
    }

    public function toArray(): array
    {
        return [
            ...[
                'message' => $this->output->status->message,
                'error' => $this->output->status->statusCode,
                'errors' => $this->output->errors
            ],
            ...($this->isDevelopementMode ? ['trace' => $this->trace] : [])
        ];
    }
}
