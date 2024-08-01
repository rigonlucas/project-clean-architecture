<?php

namespace Core\Modules\User\Create\Output;

use Core\Generics\Outputs\GenericOutputInterface;
use Core\Generics\Outputs\OutputStatus;
use Core\Generics\Presenters\ToArrayPresenterInterface;
use Core\Modules\User\Create\Presenter\CreateUserErrorPresenterInterface;

readonly class CreateUserOutputInterfaceError implements GenericOutputInterface
{
    public function __construct(
        public OutputStatus $status,
        public string $message,
        public ?array $errors = [],
        public ?array $trace = [],
        public bool $isDevelopementMode = false
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatus(): OutputStatus
    {
        return $this->status;
    }

    public function getPresenter(): ToArrayPresenterInterface
    {
        return new CreateUserErrorPresenterInterface($this, $this->trace, $this->isDevelopementMode);
    }
}
