<?php

namespace Core\Modules\User\Commons\Entities\Traits;

use Core\Modules\User\Commons\Entities\UserEntity;
use DateTimeInterface;
use SensitiveParameter;

trait HasUserEntityBuilder
{
    public static function create(
        string $name,
        string $email,
        #[SensitiveParameter]
        string $password,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->birthday = $birthday;
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->password = $password;
        $userEntity->validateAge();

        return $userEntity;
    }

    public static function details(
        int $id,
        string $name,
        string $email,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->id = $id;
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->birthday = $birthday;

        return $userEntity;
    }

    public static function update(
        int $id,
        string $name,
        string $email,
        #[SensitiveParameter]
        string $password,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->birthday = $birthday;
        $userEntity->id = $id;
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->password = $password;
        $userEntity->validateAge();

        return $userEntity;
    }

    public static function delete(int $id): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->setId($id);

        return $userEntity;
    }
}
