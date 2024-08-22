<?php

namespace Core\Domain\Entities\User\Traits;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\ValueObjects\EmailValueObject;
use Core\Generics\Exceptions\InvalidEmailException;
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
        string $email,
        #[SensitiveParameter]
        string $password,
        ?AccountEntity $account,
        UuidInterface $uuid = null,
        ?DateTimeInterface $birthday = null,
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->setBirthday($birthday);
        $userEntity->setName($name);
        $userEntity->setEmail(new EmailValueObject($email, false));
        $userEntity->setPassword($password);
        $userEntity->setUuid($uuid);
        $userEntity->setAccount($account);

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
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->setId($id);
        $userEntity->setName($name);
        $userEntity->setEmail(new EmailValueObject($email, false));
        $userEntity->setBirthday($birthday);
        $userEntity->setUuid($uuid);

        return $userEntity;
    }

    /**
     * @throws InvalidEmailException
     */
    public static function forUpdate(
        int $id,
        string $name,
        string $email,
        #[SensitiveParameter]
        string $password,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->setBirthday($birthday);
        $userEntity->setId($id);
        $userEntity->setName($name);
        $userEntity->setEmail(new EmailValueObject($email, false));
        $userEntity->setPassword($password);

        return $userEntity;
    }

    public static function forDelete(int $id): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->setId($id);

        return $userEntity;
    }

    public static function forIdentify(int $id): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->setId($id);

        return $userEntity;
    }
}
