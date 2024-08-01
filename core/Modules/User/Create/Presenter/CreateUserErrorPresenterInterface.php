<?php

namespace Core\Modules\User\Create\Presenter;

use Core\Generics\Presenters\ToArrayPresenterInterface;
use Core\Modules\User\Create\Output\CreateUserOutputInterfaceError;

readonly class CreateUserErrorPresenterInterface implements ToArrayPresenterInterface
{

    public function __construct(
        private CreateUserOutputInterfaceError $output,
        public ?array $trace = [],
        private bool $isDevelopementMode = false
    ) {
    }

    public function toArray(): array
    {
        return [
            ...[
                'message' => $this->output->getMessage(),
                'errors' => $this->output->errors
            ],
            ...($this->isDevelopementMode ? ['trace' => $this->trace] : [])
        ];
    }
}
