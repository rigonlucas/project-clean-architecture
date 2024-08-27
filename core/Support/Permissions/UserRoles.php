<?php

namespace Core\Support\Permissions;

use Core\Support\Permissions\Access\GeneralPermissions;
use ReflectionClass;

class UserRoles
{
    public const int ADMIN = GeneralPermissions::READ | GeneralPermissions::WRITE | GeneralPermissions::DELETE | GeneralPermissions::EXECUTE;
    public const int EDITOR = GeneralPermissions::READ | GeneralPermissions::WRITE;
    public const int VIEWER = GeneralPermissions::READ;

    public const array ROLES = [
        self::ADMIN,
        self::EDITOR,
        self::VIEWER,
    ];

    public static function isInvalidRole(int $role): bool
    {
        return !static::isValidRole($role);
    }

    public static function isValidRole(int $role): bool
    {
        return in_array($role, array_values(static::ROLES));
    }

    public static function getPermissionsForRole(int $role): array
    {
        $permissions = [];
        $reflection = new ReflectionClass(GeneralPermissions::class);
        $constants = $reflection->getConstants();

        foreach ($constants as $name => $value) {
            if ($role & $value) {
                $permissions[$value] = $name;
            }
        }

        return $permissions;
    }
}
