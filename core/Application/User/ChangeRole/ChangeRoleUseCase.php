<?php

namespace Core\Application\User\ChangeRole;

use Core\Application\User\ChangeRole\Inputs\ChangeRoleInput;
use Core\Application\User\Commons\Exceptions\UserNotFountException;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Support\Exceptions\ForbidenException;
use Core\Support\Exceptions\InvalidRoleException;
use Core\Support\Http\ResponseStatusCodeEnum;
use Core\Support\Permissions\UserRoles;

readonly class ChangeRoleUseCase
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
    public function execute(ChangeRoleInput $input): void
    {
        if ($input->authenticatedUser->hasNotPermission(UserRoles::ADMIN)) {
            throw new ForbidenException(
                message: 'You do not have permission to change the role',
                code: ResponseStatusCodeEnum::FORBIDDEN->value
            );
        }

        if (UserRoles::isValidRole($input->role)) {
            throw new InvalidRoleException(
                message: 'Invalid role',
                code: ResponseStatusCodeEnum::BAD_REQUEST->value
            );
        }

        $userForChange = $this->userRepository->findByUuid($input->userUuid);
        if (!$userForChange) {
            throw new UserNotFountException(
                message: 'User not found',
                code: ResponseStatusCodeEnum::NOT_FOUND->value
            );
        }
        $userForChange->checkUsersAreFromSameAccount($input->authenticatedUser);

        if ($input->role === $userForChange->getPermissions()) {
            return;
        }

        $userForChange->setPermissions($input->role);
        $this->userCommand->changeRole($userForChange);
    }
}
