<?php

namespace Core\Domain\Collections\User;

use Core\Domain\Entities\User\UserEntity;
use Core\Support\Collections\CollectionBase;
use Core\Support\Collections\Paginations\Simple\HasDefaultPagination;

class UserCollection extends CollectionBase
{
    use HasDefaultPagination;

    public function add(UserEntity $user): self
    {
        $this->items[] = $user;
        return $this;
    }

    public function toArray(): array
    {
        return array_map(
            fn(UserEntity $user) => [
                'uuid' => $user->getUuid(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'account' => [
                    'uuid' => $user->getAccount()->getUuid(),
                    'name' => $user->getAccount()->getName(),
                ],
                'birthday' => $user->getBirthday()->getTimestamp(),
                'role' => $user->getRoleName(),
            ],
            $this->items
        );
    }
}
