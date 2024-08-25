<?php

namespace Core\Domain\Entities\User\Traits;

use Core\Support\Exceptions\InvalidRoleException;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;

trait HasUserRoleTrait
{
    private ?int $permissions = null;

    public function hasNotPermission(int $permission): bool
    {
        return !$this->hasPermission($permission);
    }

    public function hasPermission(int $permission): bool
    {
        return ($this->permissions & $permission) === $permission;
    }

    public function getPermissions(): ?int
    {
        return $this->permissions;
    }

    public function setPermissions(int $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function hasNotAnyPermissionFromArray(array $permissions): bool
    {
        return !$this->hasAnyPermissionFromArray($permissions);
    }

    public function hasAnyPermissionFromArray(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
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
                ResponseStatus::INTERNAL_SERVER_ERROR->value
            ),
        };
    }
}