<?php

namespace Infra\Database\User\Command;

use App\Models\User;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Domain\Entities\User\UserEntity;

class UserCommand implements UserCommandInterface
{

    public function create(UserEntity $userEntity): UserEntity
    {
        $userModel = new User();
        $userModel->uuid = $userEntity->getUuid()->toString();
        $userModel->name = $userEntity->getName();
        $userModel->account_uuid = $userEntity?->getAccount()?->getUuid()?->toString();
        $userModel->email = $userEntity->getEmail();
        $userModel->password = $userEntity->getPassword();
        $userModel->birthday = $userEntity->getBirthday();
        $userModel->save();

        return $userEntity;
    }

    public function changeRole(UserEntity $userEntity): void
    {
        User::query()
            ->where('uuid', '=', $userEntity->getUuid())
            ->update([
                'role' => $userEntity->getPermissions()
            ]);
    }

    public function update(UserEntity $userEntity): UserEntity
    {
        User::query()
            ->where('uuid', '=', $userEntity->getUuid())
            ->update([
                'name' => $userEntity->getName(),
                'email' => $userEntity->getEmail(),
                'password' => $userEntity->getPassword(),
                'birthday' => $userEntity->getBirthday()
            ]);

        return $userEntity;
    }
}
