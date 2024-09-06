<?php

namespace Core\Application\User\ChangeRole\Inputs;

use Core\Domain\Entities\Shared\User\Root\UserEntity;

readonly class ChangeUserRoleInput
{
    public function __construct(
        public UserEntity $authenticatedUser,
        public string $userUuid,
        public int $role
    ) {
    }
}
