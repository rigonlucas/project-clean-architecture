<?php

namespace Core\Domain\Entities\Shared\User\Root;

use Core\Domain\Entities\Shared\Account\Root\AccountEntity;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Support\Exceptions\InvalideRules\InvalidEmailException;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use SensitiveParameter;

trait HasUserEntityBuilder
{
    /**
     * @throws InvalidEmailException
     */
    public static function forCreate(
        string $name,
        EmailValueObject $email,
        #[SensitiveParameter]
        string $password,
        ?AccountEntity $account,
        UuidInterface $uuid = null,
        ?DateTimeInterface $birthday = null,
        int $role = 0
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->setBirthday($birthday);
        $userEntity->setName($name);
        $userEntity->setEmail($email);
        $userEntity->setPassword($password);
        $userEntity->setUuid($uuid);
        $userEntity->setAccount($account);
        $userEntity->setPermissions($role);

        return $userEntity;
    }

    /**
     * @throws InvalidEmailException
     */
    public static function forDetail(
        int $id,
        string $name,
        string $email,
        UuidInterface $uuid,
        ?AccountEntity $account,
        ?DateTimeInterface $birthday = null,
        int $role = 0
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->setId($id);
        $userEntity->setName($name);
        $userEntity->setEmail(new EmailValueObject($email, false));
        $userEntity->setBirthday($birthday);
        $userEntity->setAccount($account);
        $userEntity->setUuid($uuid);
        $userEntity->setPermissions($role);

        return $userEntity;
    }

    /**
     * @throws InvalidEmailException
     */
    public static function forUpdate(
        UuidInterface $uuid,
        string $name,
        string $email,
        #[SensitiveParameter]
        string $password,
        ?DateTimeInterface $birthday = null,
        int $role = 0
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->setBirthday($birthday);
        $userEntity->setUuid($uuid);
        $userEntity->setName($name);
        $userEntity->setEmail(new EmailValueObject($email, false));
        $userEntity->setPassword($password);
        $userEntity->setPermissions($role);

        return $userEntity;
    }

    public static function forDelete(UuidInterface $uuid, int $role = 0): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->setUuid($uuid);
        $userEntity->setPermissions($role);

        return $userEntity;
    }

    public static function forIdentify(
        int $id,
        UuidInterface $uuid,
        int $role = 0,
        ?UuidInterface $accountUuid = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->setId($id);
        $userEntity->setPermissions($role);
        $userEntity->setUuid($uuid);
        if ($accountUuid) {
            $userEntity->setAccount(AccountEntity::forIdentify($accountUuid));
        }

        return $userEntity;
    }
}
