<?php

namespace Infra\Services\Framework\Adapters;

use Core\Adapters\Framework\Contracts\UuidContract;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidAdapter implements UuidContract
{

    public function uuid7Generate(?DateTimeInterface $dateTime = null): UuidInterface
    {
        return Uuid::uuid7($dateTime);
    }

    public function uuidFromString(string $uuid): UuidInterface
    {
        return Uuid::fromString($uuid);
    }
}
