<?php

namespace Infra\Persistence\User\Command;

use App\Models\User;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Create\Gateways\CreateUserInterface;

class CreateUserCommand implements CreateUserInterface
{

    public function create(UserEntity $userEntity): UserEntity
    {
        $userModel = new User();
        $userModel->name = $userEntity->name;
        $userModel->email = $userEntity->email;
        $userModel->password = $userEntity->password;
        $userModel->save();

        return $userEntity;
    }
}
