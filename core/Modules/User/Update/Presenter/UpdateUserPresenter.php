<?php

namespace Core\Modules\User\Update\Presenter;

use Core\Generics\Presenters\GenericPresenter;
use Core\Modules\User\Update\Output\UpdateUserOutput;

readonly class UpdateUserPresenter implements GenericPresenter
{
    public function __construct(private UpdateUserOutput $output)
    {
    }

    public function getOutput(): UpdateUserOutput
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
