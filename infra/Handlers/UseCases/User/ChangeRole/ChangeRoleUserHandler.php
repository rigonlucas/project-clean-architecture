<?php

namespace Infra\Handlers\UseCases\User\ChangeRole;

use Core\Application\User\ChangeRole\ChangeUserRoleUseCase;
use Core\Application\User\ChangeRole\Inputs\ChangeUserRoleInput;
use Core\Application\User\Shared\Gateways\UserCommandInterface;
use Core\Application\User\Shared\Gateways\UserMapperInterface;

readonly class ChangeRoleUserHandler
{
    public function __construct(
        private UserCommandInterface $userCommand,
        private UserMapperInterface $userMapper,
    ) {
    }

    public function handle(ChangeUserRoleInput $input): void
    {
        $changeRoleUseCase = new ChangeUserRoleUseCase(
            userCommand: $this->userCommand,
            userMapper: $this->userMapper
        );
        $changeRoleUseCase->execute($input);
    }
}
