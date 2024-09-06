<?php

namespace Core\Services\Framework\Contracts;

use DateTimeInterface;

interface UlidContract
{
    public function generate(?DateTimeInterface $dateTime = null): string;

    public function fromString(string $uuid): array;
}