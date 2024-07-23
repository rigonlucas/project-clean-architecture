<?php

namespace Core\Modules\User\Create\Presenter;

use Core\Generics\Presenters\GenericPresenter;
use Core\Modules\User\Create\Output\CreateUserOutput;

readonly class CreateUserPresenter implements GenericPresenter
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
                'email' => $this->output->userEntity->getEmail(),
                'birthday' => $this->output->userEntity->getBirthday()->format('Y-m-d')
            ]
        ];
    }
}
