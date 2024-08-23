<?php

namespace Core\Domain\Entities\User\Traits;

use Core\Domain\Entities\User\UserEntity;
use Core\Support\Exceptions\ForbidenException;
use Core\Support\Http\ResponseStatusCodeEnum;

trait HasUserAccountValidator
{
    /**
     * @throws ForbidenException
     */
    public function checkUsersAreFromSameAccount(UserEntity $userToCompare): void
    {
        if ($this->getAccount()->getId() !== $userToCompare->getAccount()->getId()) {
            throw new ForbidenException(
                message: 'You do not have permission to change the role',
                code: ResponseStatusCodeEnum::FORBIDDEN->value
            );
        }
    }
}
