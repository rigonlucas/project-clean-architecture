<?php

namespace Infra\Handlers\UseCases\User\Create;

use Core\Domain\Entities\User\UserEntity;

readonly class CreateUserOutput
{
    public function __construct(public UserEntity $userEntity)
    {
    }
}
