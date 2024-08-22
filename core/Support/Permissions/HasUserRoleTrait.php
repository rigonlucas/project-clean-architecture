<?php

namespace Core\Support\Permissions;

use Core\Support\Exceptions\InvalidRoleException;
use Core\Support\Http\ResponseStatusCodeEnum;
use Core\Support\Permissions\Access\UserRoles;

trait HasUserRoleTrait
{
    private int $permissions = 0;
    private int $role = 0;

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

    public function getRole(): int
    {
        return $this->role;
    }

    public function setRole(int $role): void
    {
        $this->role = $role;
        $this->permissions = $role;
    }

    public function hasRolePermission(int $permission): bool
    {
        return ($this->role & $permission) === $permission;
    }

    /**
     * @throws InvalidRoleException
     */
    public function getRoleName(): string
    {
        return match ($this->role) {
            UserRoles::ADMIN => 'ADMIN',
            UserRoles::EDITOR => 'EDITOR',
            UserRoles::VIEWER => 'VIEWER',
            default => throw new InvalidRoleException("Invalid role", ResponseStatusCodeEnum::FORBIDDEN->value),
        };
    }
}