<?php

namespace Core\Application\User\Update\Output;

use Core\Application\User\Commons\Entities\User\UserEntity;

readonly class UpdateUserOutput
{
    public function __construct(public UserEntity $userEntity)
    {
    }
}
