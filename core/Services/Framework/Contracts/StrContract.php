<?php

namespace Core\Services\Framework\Contracts;

interface StrContract
{
    public function random(int $length = 16): string;

    public function title(string $value): string;
}
