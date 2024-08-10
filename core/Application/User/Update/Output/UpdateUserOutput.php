<?php

namespace Core\Application\User\Update\Output;

use Core\Domain\Entities\User\UserEntity;

readonly class UpdateUserOutput
{
    public function __construct(public UserEntity $userEntity)
    {
    }
}
