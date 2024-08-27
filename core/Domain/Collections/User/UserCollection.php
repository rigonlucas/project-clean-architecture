<?php

namespace Core\Domain\Collections\User;

use Core\Domain\Entities\User\UserEntity;
use Core\Support\Collections\CollectionBase;
use Core\Support\Collections\Paginations\Simple\HasDefaultPagination;
use Core\Support\Exceptions\InvalideRules\InvalidRoleException;

class UserCollection extends CollectionBase
{
    use HasDefaultPagination;

    public function __construct(private readonly UserEntity $requireUserEntity)
    {
    }

    public function add(UserEntity $user): self
    {
        $this->items[] = $user;
        return $this;
    }

    /**
     * @return array
     * @throws InvalidRoleException
     */
    public function toArray(): array
    {
        return array_map(
            fn(UserEntity $user) => [
                'uuid' => $user->getUuid(),
                'name' => $user->getName(),
                'email' => $user->getEmailWithAccessControl($this->requireUserEntity)->get(),
                'birthday' => $user->getBirthday()->getTimestamp(),
                'role' => $user->getRoleName(),
            ],
            $this->items
        );
    }
}
