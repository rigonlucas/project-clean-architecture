<?php

namespace Infra\Persistence\User\Repository;

use App\Models\User;
use Core\Modules\User\Commons\Entities\UserEntity;
use Core\Modules\User\Commons\Exceptions\InvalidAgeException;
use Core\Modules\User\Commons\Gateways\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @throws InvalidAgeException
     */
    public function findById(int $id): ?UserEntity
    {
        $user = User::query()->find($id);
        if ($user) {
            return new UserEntity(
                name: $user->name,
                email: $user->email
            );
        }

        return null;
    }
}
