<?php

namespace Core\Services\Framework\Contracts;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

interface UuidContract
{
    public function uuid7Generate(?DateTimeInterface $dateTime = null): UuidInterface;

    public function uuidFromString(string $uuid): UuidInterface;
}