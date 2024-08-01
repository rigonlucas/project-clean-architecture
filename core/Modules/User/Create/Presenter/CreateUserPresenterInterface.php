<?php

namespace Core\Modules\User\Create\Presenter;

use Core\Generics\Presenters\ToArrayPresenterInterface;
use Core\Modules\User\Create\Output\CreateUserOutputInterface;

readonly class CreateUserPresenterInterface implements ToArrayPresenterInterface
{
    public function __construct(private CreateUserOutputInterface $output)
    {
    }

    public function getOutput(): CreateUserOutputInterface
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
