<?php

namespace Core\Modules\User\Create\Presenter;

use Core\Generics\Presenters\GenericPresenter;
use Core\Modules\User\Create\Output\CreateUserOutputError;

readonly class CreateUserErrorPresenter implements GenericPresenter
{

    public function __construct(
        private CreateUserOutputError $output,
        public ?array $trace = [],
        private bool $isDevelopementMode = false
    ) {
    }

    public function toArray(): array
    {
        return [
            ...[
                'errors' => $this->output->errors
            ],
            ...($this->isDevelopementMode ? ['trace' => $this->trace] : [])
        ];
    }
}
