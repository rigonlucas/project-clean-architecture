<?php

namespace Core\Domain\Entities\Shared\User\Root;

use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\InvalideRules\InvalidComparationException;
use Core\Support\Exceptions\InvalideRules\InvalidEmailException;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;
use DateTime;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

class UserEntity
{
    use HasUserEntityBuilder;
    use HasUserRoleTrait;

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

    /**
     * @throws InvalidEmailException
     */
    public function setEmail(EmailValueObject $email): self
    {
        $this->email = new EmailValueObject($email, false);
        return $this;
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

    public function isUserOwner(): bool
    {
        return $this->userOwner;
    }

    public function setUserOwner(bool $userOwner): self
    {
        $this->userOwner = $userOwner;
        return $this;
    }

    /**
     * @throws ForbidenException
     * @throws InvalidComparationException
     */
    public function checkUsersAreFromSameAccount(UserEntity $userToCompare): void
    {
        if (!$this->getAccount()->getUuid()->equals($userToCompare->getAccount()->getUuid())) {
            throw new ForbidenException(
                message: 'You do not have permission to change the role'
            );
        }

        if ($this->getUuid()->equals($userToCompare->getUuid())) {
            throw new InvalidComparationException(
                message: 'You can not compare the same user',
                code: ResponseStatus::INTERNAL_SERVER_ERROR->value
            );
        }
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(?UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getAccount(): ?AccountEntity
    {
        return $this->account;
    }

    public function setAccount(?AccountEntity $account): self
    {
        $this->account = $account;
        return $this;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(
        #[SensitiveParameter]
        string $password
    ): self {
        $this->password = $password;
        return $this;
    }

    public function getBirthday(): ?DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;
        return $this;
    }
}
