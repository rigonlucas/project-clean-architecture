<?php

namespace Core\Domain\Entities\User;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\Traits\HasUserEntityBuilder;
use Core\Domain\Entities\User\Traits\HasUserRoleTrait;
use Core\Domain\Entities\User\Traits\UserEntityAcessors;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Exceptions\ForbidenException;
use Core\Support\Exceptions\InvalidComparationException;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;
use DateTime;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class UserEntity
{
    use HasUserEntityBuilder;
    use UserEntityAcessors;
    use HasUserRoleTrait;

    private ?int $id = null;
    private string $name;
    private ?EmailValueObject $email = null;
    private ?string $password;
    private UuidInterface $uuid;
    private ?DateTimeInterface $birthday;
    private ?AccountEntity $account;

    private function __construct()
    {
    }

    public function underAge(): bool
    {
        return $this->birthday->diff(new DateTime())->y < 18;
    }

    /**
     * Business rule:
     * This method is used to check if the user has permission to see the email
     *      - If the user is an admin, he can see the email
     *      - If the user is not an admin, he can not see the email
     *      - If the user has no permission defined, he can not see the email
     * @throws ForbidenException
     */
    public function getEmail(): EmailValueObject
    {
        if (is_null($this->email)) {
            return new EmailValueObject('', false);
        }

        if (is_null($this->getPermissions())) {
            throw new ForbidenException(
                message: 'You do not have permission to see the email. No permission defined'
            );
        }

        if ($this->hasNotPermission(UserRoles::ADMIN)) {
            return $this->email->supress();
        }

        return $this->email;
    }

    /**
     * @throws ForbidenException
     * @throws InvalidComparationException
     */
    public function checkUsersAreFromSameAccount(UserEntity $userToCompare): void
    {
        if ($this->getAccount()->getId() !== $userToCompare->getAccount()->getId()) {
            throw new ForbidenException(
                message: 'You do not have permission to change the role'
            );
        }

        if ($this->getId() === $userToCompare->getId()) {
            throw new InvalidComparationException(
                message: 'You can not compare the same user',
                code: ResponseStatus::INTERNAL_SERVER_ERROR->value
            );
        }
    }
}
