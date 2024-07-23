<?php

namespace Core\Modules\User\Update\Presenter;

use Core\Generics\Presenters\GenericPresenter;
use Core\Modules\User\Create\Output\CreateUserOutput;

class UpdateUserPresenter implements GenericPresenter
{
    public function __construct(private CreateUserOutput $output)
    {
    }

    public function getOutput(): CreateUserOutput
    {
        return $this->output;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->output->status->message,
            'data' => [
                'id' => $this->output->userEntity->getId(),
                'name' => $this->output->userEntity->getName(),
                'email' => $this->output->userEntity->getEmail()
            ]
        ];
    }
}
