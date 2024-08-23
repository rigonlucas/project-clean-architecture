<?php

namespace Core\Application\User\ChangeRole;

use Core\Application\User\ChangeRole\Inputs\ChangeUserRoleInput;
use Core\Application\User\Commons\Exceptions\UserNotFountException;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Support\Exceptions\ForbidenException;
use Core\Support\Exceptions\InvalidRoleException;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;

readonly class ChangeUserRoleUseCase
{
    public function __construct(
        private UserCommandInterface $userCommand,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws UserNotFountException
     * @throws InvalidRoleException
     * @throws ForbidenException
     */
    public function execute(ChangeUserRoleInput $input): void
    {
        $this->validateAccessPolicies($input);

        if (UserRoles::isValidRole($input->role)) {
            throw new InvalidRoleException(
                message: 'Invalid role',
                code: ResponseStatus::BAD_REQUEST->value
            );
        }

        $userForChange = $this->userRepository->findByUuid($input->userUuid);
        if (!$userForChange) {
            throw new UserNotFountException(
                message: 'User not found',
                code: ResponseStatus::NOT_FOUND->value
            );
        }
        $userForChange->checkUsersAreFromSameAccount($input->authenticatedUser);

        if ($input->role === $userForChange->getPermissions()) {
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
