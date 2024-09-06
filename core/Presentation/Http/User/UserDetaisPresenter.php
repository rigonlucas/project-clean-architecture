<?php

namespace Core\Presentation\Http\User;

use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Support\Presentation\PresentationBase;

class UserDetaisPresenter extends PresentationBase
{
    public function __construct(UserEntity $userEntity)
    {
        $this->data = [
            'uuid' => $userEntity->getUuid()->toString(),
            'name' => $userEntity->getName(),
            'email' => $userEntity->getEmail(),
            'birthday' => $userEntity->getBirthday(),
        ];
    }
}
