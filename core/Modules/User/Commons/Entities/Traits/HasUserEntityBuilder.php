<?php

namespace Core\Modules\User\Commons\Entities\Traits;

use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use DateTimeInterface;

trait HasUserEntityBuilder
{
    /**
     * @throws InvalidAgeException
     */
    public static function create(
        string $name,
        string $email,
        string $password,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->birthday = $birthday;
        $userEntity->validateAge();

        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->password = $password;

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

    /**
     * @throws InvalidAgeException
     */
    public static function update(
        int $id,
        string $name,
        string $email,
        string $password,
        ?DateTimeInterface $birthday = null
    ): UserEntity {
        $userEntity = new UserEntity();
        $userEntity->birthday = $birthday;
        $userEntity->validateAge();

        $userEntity->id = $id;
        $userEntity->name = $name;
        $userEntity->email = $email;
        $userEntity->password = $password;

        return $userEntity;
    }

    public static function delete(int $id): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->setId($id);

        return $userEntity;
    }
}
