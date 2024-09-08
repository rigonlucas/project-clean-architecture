<?php

namespace Infra\Services\Framework\Adapters;

use Core\Services\Framework\Contracts\UuidContract;
use Core\Support\HasSingletonTrait;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Uid\Ulid;

class UuidAdapter implements UuidContract
{
    use HasSingletonTrait;

    public function uuid7Generate(?DateTimeInterface $dateTime = null): UuidInterface
    {
        return Uuid::uuid7($dateTime);
    }

    public function uuidFromString(string $uuid): UuidInterface
    {
        return Uuid::fromString($uuid);
    }

    public function ulidGenerate(): string
    {
        return Ulid::generate();
    }
}
