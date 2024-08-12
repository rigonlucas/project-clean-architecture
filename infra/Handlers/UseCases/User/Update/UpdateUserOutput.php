<?php

namespace Infra\Handlers\UseCases\User\Update;

use Core\Domain\Entities\User\UserEntity;

readonly class UpdateUserOutput
{
    public function __construct(public UserEntity $userEntity)
    {
    }
}
