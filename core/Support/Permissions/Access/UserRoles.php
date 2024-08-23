<?php

namespace Core\Support\Permissions\Access;

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

    public static function isValidRole(int $role): bool
    {
        return in_array($role, array_keys(self::ROLES));
    }
}
