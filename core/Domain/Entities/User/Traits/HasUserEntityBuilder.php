<?php

namespace Core\Domain\Entities\User\Traits;

use Core\Domain\Entities\Account\AccountEntity;
use Core\Domain\Entities\User\UserEntity;
use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use SensitiveParameter;

trait HasUserEntityBuilder
{
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
        $userEntity->setEmail($email);
        $userEntity->setPassword($password);
        $userEntity->setUuid($uuid);
        $userEntity->setAccount($account);

        return $userEntity;
    }

    public static function record(
        int $id,
        string $name,
        string $email,
        UuidInterface $uuid,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->setId($id);
        $userEntity->setName($name);
        $userEntity->setEmail($email);
        $userEntity->setBirthday($birthday);
        $userEntity->setUuid($uuid);

        return $userEntity;
    }

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
        $userEntity->setEmail($email);
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
