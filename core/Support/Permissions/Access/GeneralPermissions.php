<?php

namespace Core\Support\Permissions\Access;

class GeneralPermissions
{
    public const int READ = 1 << 0;  // 0001 -> 1
    public const int WRITE = 1 << 1; // 0010 -> 2
    public const int DELETE = 1 << 2; // 0100 -> 4
    public const int EXECUTE = 1 << 3; // 1000 -> 8
}
