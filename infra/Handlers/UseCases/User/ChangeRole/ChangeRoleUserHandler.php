<?php

namespace Infra\Handlers\UseCases\User\ChangeRole;

use Core\Application\User\ChangeRole\ChangeUserRoleUseCase;
use Core\Application\User\ChangeRole\Inputs\ChangeUserRoleInput;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;

readonly class ChangeRoleUserHandler
{
    public function __construct(
        private UserCommandInterface $userCommand,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(ChangeUserRoleInput $input): void
    {
        $changeRoleUseCase = new ChangeUserRoleUseCase(
            userCommand: $this->userCommand,
            userRepository: $this->userRepository
        );
        $changeRoleUseCase->execute($input);
    }
}
