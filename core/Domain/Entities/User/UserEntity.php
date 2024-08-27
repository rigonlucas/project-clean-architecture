<?php

namespace Core\Domain\Entities\User;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\Traits\HasUserEntityBuilder;
use Core\Domain\Entities\User\Traits\HasUserRoleTrait;
use Core\Domain\Entities\User\Traits\UserEntityAcessors;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\InvalideRules\InvalidComparationException;
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

    private bool $userOwner = false;

    private function __construct()
    {
    }

    public function underAge(): bool
    {
        return $this->birthday->diff(new DateTime())->y < 18;
    }

    public function getEmail(): EmailValueObject
    {
        if (is_null($this->email)) {
            return new EmailValueObject('', false);
        }

        return $this->email;
    }

    public function getEmailWithAccessControl(UserEntity $requireUserEntity): EmailValueObject
    {
        $permissions = [
            UserRoles::ADMIN,
            UserRoles::EDITOR,
        ];
        if ($requireUserEntity->hasNotAnyPermissionFromArray($permissions) && !$this->isUserOwner()) {
            return new EmailValueObject('', false);
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

    /**
     * @throws ForbidenException
     */
    public function canUpateAnUser(UuidInterface $informedUuid): void
    {
        if (!$this->getUuid()->equals($informedUuid) && $this->hasPermission(UserRoles::ADMIN)) {
            throw new ForbidenException(
                message: 'You do not have permission to update this user',
            );
        }
    }
}
