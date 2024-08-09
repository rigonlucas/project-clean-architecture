<?php

namespace Core\Presentation\User;

use Core\Application\User\Commons\Entities\User\UserEntity;
use Core\Generics\Presenters\PresenterWithDataAttribute;
use Core\Generics\Presenters\ToArrayPresenter;

class UpdateUserPresenter implements ToArrayPresenter, PresenterWithDataAttribute
{
    private array $data;

    public function __construct(UserEntity $userEntity)
    {
        $this->data = [
            'id' => $userEntity->getId(),
            'name' => $userEntity->getName(),
            'email' => $userEntity->getEmail(),
            'birthday' => $userEntity->getBirthday()->format('Y-m-d'),
            'uuid' => $userEntity->getUuid()->toString()
        ];
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function withDataAttribute(): UpdateUserPresenter
    {
        $this->data['data'] = $this->data;
        return $this;
    }
}
