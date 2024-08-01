<?php

namespace Core\Modules\User\Update\Output;

use Core\Generics\Outputs\GenericOutputInterface;
use Core\Generics\Outputs\OutputStatus;
use Core\Generics\Presenters\ToArrayPresenterInterface;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Update\Presenter\UpdateUserPresenterInterface;

class UpdateUserOutputInterface implements GenericOutputInterface
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
        return new UpdateUserPresenterInterface($this);
    }
}
