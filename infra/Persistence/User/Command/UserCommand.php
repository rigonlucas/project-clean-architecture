<?php

namespace Infra\Persistence\User\Command;

use App\Models\User;
use Core\Modules\User\Commons\Entities\UserEntity as CreateUserEntity;
use Core\Modules\User\Commons\Gateways\UserCommandInterface;

class UserCommand implements UserCommandInterface
{

    public function create(CreateUserEntity $userEntity): CreateUserEntity
    {
        $userModel = new User();
        $userModel->name = $userEntity->getName();
        $userModel->email = $userEntity->getEmail();
        $userModel->password = $userEntity->getPassword();
        $userModel->save();

        return $userEntity;
    }

    public function update(CreateUserEntity $userEntity): CreateUserEntity
    {
        User::query()
            ->where('id', '=', $userEntity->getId())
            ->update([
                'name' => $userEntity->getName(),
                'email' => $userEntity->getEmail(),
                'password' => $userEntity->getPassword()
            ]);

        return $userEntity;
    }
}
