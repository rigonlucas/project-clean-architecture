<?php

namespace Core\Application\User\ChangeRole\Inputs;

use Core\Domain\Entities\User\UserEntity;

readonly class ChangeUserRoleInput
{
    public function __construct(
        public UserEntity $authenticatedUser,
        public string $userUuid,
        public int $role
    ) {
    }
}
