<?php

namespace Core\Modules\User\Update\Output;

use Core\Generics\Outputs\GenericOutput;
use Core\Generics\Outputs\OutputStatus;
use Core\Generics\Presenters\GenericPresenter;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Update\Presenter\UpdateUserPresenter;

class UpdateUserOutput implements GenericOutput
{
    public function __construct(public OutputStatus $status, public UserEntity $userEntity, public string $message = '')
    {
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
        return new UpdateUserPresenter($this);
    }
}
