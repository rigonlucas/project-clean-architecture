<?php

namespace Core\Application\User\Create\Output;

use Core\Application\User\Commons\Entities\User\UserEntity;

readonly class CreateUserOutput
{
    public function __construct(public UserEntity $userEntity)
    {
    }
}
