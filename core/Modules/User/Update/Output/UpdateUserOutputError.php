<?php

namespace Core\Modules\User\Update\Output;

use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputStatus;
use Core\Generics\Presenters\GenericPresenter;
use Core\Modules\User\Update\Presenter\UpdateUserErrorPresenter;

readonly class UpdateUserOutputError implements GenericOutput
{
    public function __construct(
        public OutputStatus $status,
        public ?array $errors = [],
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
        return new UpdateUserErrorPresenter($this, $this->trace, $this->isDevelopementMode);
    }
}
