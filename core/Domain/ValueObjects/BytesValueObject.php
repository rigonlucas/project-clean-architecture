<?php

namespace Core\Domain\ValueObjects;

class BytesValueObject
{
    private int $bytes;

    public function __construct(int $bytes)
    {
        $this->bytes = $bytes;
    }

    public function getBytes(): int
    {
        return $this->bytes;
    }

    public function getKilobytes(): float
    {
        return $this->toKilobytes();
    }

    public function toKilobytes(): float
    {
        return $this->bytes / 1024;
    }

    public function getMegabytes(): float
    {
        return $this->toMegabytes();
    }

    public function toMegabytes(): float
    {
        return $this->bytes / (1024 * 1024);
    }

    public function getGigabytes(): float
    {
        return $this->toGigabytes();
    }

    public function toGigabytes(): float
    {
        return $this->bytes / (1024 * 1024 * 1024);
    }
}