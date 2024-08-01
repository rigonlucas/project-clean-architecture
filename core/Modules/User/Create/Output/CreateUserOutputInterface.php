<?php

namespace Core\Modules\User\Create\Output;

use Core\Generics\Outputs\GenericOutputInterface;
use Core\Generics\Outputs\OutputStatus;
use Core\Generics\Presenters\ToArrayPresenterInterface;
use Core\Modules\User\Commons\Entities\User\UserEntity;
use Core\Modules\User\Create\Presenter\CreateUserPresenterInterface;

readonly class CreateUserOutputInterface implements GenericOutputInterface
{
    public function __construct(public OutputStatus $status, public UserEntity $userEntity, public string $message = '')
    {
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
        return new CreateUserPresenterInterface($this);
    }
}
