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
        $userModel->name = $userEntity->getName();
        $userModel->email = $userEntity->getEmail();
        $userModel->password = $userEntity->getPassword();
        $userModel->birthday = $userEntity->getBirthday();
        $userModel->uuid = $userEntity->getUuid()->toString();
        $userModel->account_id = $userEntity->getAccount()->getId();
        $userModel->save();

        return $userEntity->setId($userModel->id);
    }

    public function update(UserEntity $userEntity): UserEntity
    {
        User::query()
            ->where('id', '=', $userEntity->getId())
            ->update([
                'name' => $userEntity->getName(),
                'email' => $userEntity->getEmail(),
                'password' => $userEntity->getPassword(),
                'birthday' => $userEntity->getBirthday()
            ]);

        return $userEntity;
    }
}
