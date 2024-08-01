<?php

namespace Core\Modules\User\Update\Presenter;

use Core\Generics\Presenters\ToArrayPresenterInterface;
use Core\Modules\User\Update\Output\UpdateUserOutputInterface;

readonly class UpdateUserPresenterInterface implements ToArrayPresenterInterface
{
    public function __construct(private UpdateUserOutputInterface $output)
    {
    }

    public function getOutput(): UpdateUserOutputInterface
    {
        return $this->output;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->output->status->message,
            'data' => [
                'uuid' => $this->output->userEntity->getUuid()->toString(),
                'name' => $this->output->userEntity->getName(),
                'email' => $this->output->userEntity->getEmail(),
                'birthday' => $this->output->userEntity->getBirthday()->format('Y-m-d')
            ]
        ];
    }
}
