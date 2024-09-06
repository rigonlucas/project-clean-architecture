<?php

namespace Core\Application\User\ChangeRole;

use Core\Application\User\ChangeRole\Inputs\ChangeUserRoleInput;
use Core\Application\User\Shared\Exceptions\UserNotFountException;
use Core\Application\User\Shared\Gateways\UserCommandInterface;
use Core\Application\User\Shared\Gateways\UserMapperInterface;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\InvalideRules\InvalidComparationException;
use Core\Support\Exceptions\InvalideRules\InvalidRoleException;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;

readonly class ChangeUserRoleUseCase
{
    public function __construct(
        private UserCommandInterface $userCommand,
        private UserMapperInterface $userMapper,
    ) {
    }

    /**
     * @param ChangeUserRoleInput $input
     * @throws ForbidenException
     * @throws InvalidRoleException
     * @throws UserNotFountException
     * @throws InvalidComparationException
     */
    public function execute(ChangeUserRoleInput $input): void
    {
        $this->validateAccessPolicies($input);

        if (UserRoles::isInvalidRole($input->role)) {
            throw new InvalidRoleException(
                message: 'Invalid role',
                code: ResponseStatus::BAD_REQUEST->value
            );
        }

        $userForChange = $this->userMapper->findByUuid($input->userUuid);
        if (!$userForChange) {
            throw new UserNotFountException(
                message: 'User not found',
                code: ResponseStatus::NOT_FOUND->value
            );
        }
        $userForChange->checkUsersAreFromSameAccount($input->authenticatedUser);
        if ($userForChange->getPermissions() === $input->role) {
            return;
        }

        $userForChange->setPermissions($input->role);
        $this->userCommand->changeRole($userForChange);
    }

    /**
     * @throws ForbidenException
     */
    private function validateAccessPolicies(ChangeUserRoleInput $input): void
    {
        if ($input->authenticatedUser->hasNotPermission(UserRoles::ADMIN)) {
            throw new ForbidenException(
                message: 'You do not have permission to change the role'
            );
        }
    }
}
