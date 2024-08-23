<?php

namespace Infra\Handlers\UseCases\User\ChangeRole;

use Core\Application\User\ChangeRole\ChangeRoleUseCase;
use Core\Application\User\ChangeRole\Inputs\ChangeRoleInput;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;

class ChangeRoleUserHandler
{
    public function __construct(
        private UserCommandInterface $userCommand,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function handle(ChangeRoleInput $input): void
    {
        $changeRoleUseCase = new ChangeRoleUseCase(
            userCommand: $this->userCommand,
            userRepository: $this->userRepository
        );
        $changeRoleUseCase->execute($input);
    }
}
