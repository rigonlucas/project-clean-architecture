<?php

namespace Core\Modules\User\Create\outputs;

use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputStatus;
use Core\Modules\User\Commons\Entities\UserEntity;

readonly class CreateUserOutput implements GenericOutput
{
    public function __construct(public OutputStatus $status, public UserEntity $userEntity)
    {
    }

    public function getMessages(): string
    {
        return $this->status->message;
    }

    public function getStatus(): OutputStatus
    {
        return $this->status;
    }
}
