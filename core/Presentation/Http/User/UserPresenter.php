<?php

namespace Core\Presentation\Http\User;

use Core\Domain\Entities\User\UserEntity;
use Core\Support\Presentation\PresentationBase;

class UserPresenter extends PresentationBase
{
    public function __construct(UserEntity $userEntity)
    {
        $this->data = [
            'uuid' => $userEntity->getUuid()->toString()
        ];
    }
}
