<?php

namespace Core\Presentation\User;

use Core\Application\User\Commons\Entities\User\UserEntity;
use Core\Generics\Presenters\ToArrayPresenterInterface;

readonly class UpdateUserPresenter implements ToArrayPresenterInterface
{
    public function __construct(private UserEntity $userEntity)
    {
    }

    public function toArray(): array
    {
        return [
            'data' => [
                'uuid' => $this->userEntity->getUuid()->toString(),
                'name' => $this->userEntity->getName(),
                'email' => $this->userEntity->getEmail(),
                'birthday' => $this->userEntity->getBirthday()->format('Y-m-d')
            ]
        ];
    }
}
