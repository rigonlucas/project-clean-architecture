<?php

namespace Core\Domain\Entities\User\Traits;

use Core\Support\Exceptions\InvalidRoleException;
use Core\Support\Http\ResponseStatusCodeEnum;
use Core\Support\Permissions\Access\UserRoles;

trait HasUserRoleTrait
{
    private int $permissions = 0;

    public function hasNotPermission(int $permission): bool
    {
        return !$this->hasPermission($permission);
    }

    public function hasPermission(int $permission): bool
    {
        return ($this->permissions & $permission) === $permission;
    }

    public function getPermissions(): int
    {
        return $this->permissions;
    }

    public function setPermissions(int $permissions): void
    {
        $this->permissions = $permissions;
    }

    /**
     * @throws InvalidRoleException
     */
    public function getRoleName(): string
    {
        return match ($this->permissions) {
            UserRoles::ADMIN => 'ADMIN',
            UserRoles::EDITOR => 'EDITOR',
            UserRoles::VIEWER => 'VIEWER',
            default => throw new InvalidRoleException(
                "Invalid role",
                ResponseStatusCodeEnum::INTERNAL_SERVER_ERROR->value
            ),
        };
    }
}