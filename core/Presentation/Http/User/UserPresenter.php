<?php

namespace Core\Presentation\Http\User;

use Core\Domain\Entities\User\UserEntity;
use Core\Generics\Presenters\PresenterWithDataAttribute;
use Core\Generics\Presenters\ToArrayPresenter;

class UserPresenter implements ToArrayPresenter, PresenterWithDataAttribute
{
    private array $data;

    public function __construct(UserEntity $userEntity)
    {
        $this->data = [
            'uuid' => $userEntity->getUuid()->toString()
        ];
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function withDataAttribute(): UserPresenter
    {
        $this->data['data'] = $this->data;
        return $this;
    }
}
