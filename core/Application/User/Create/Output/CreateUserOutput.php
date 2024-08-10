<?php

namespace Core\Application\User\Create\Output;

use Core\Domain\Entities\User\UserEntity;

readonly class CreateUserOutput
{
    public function __construct(public UserEntity $userEntity)
    {
    }
}
