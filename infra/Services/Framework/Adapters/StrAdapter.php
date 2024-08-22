<?php

namespace Infra\Services\Framework\Adapters;

use Core\Services\Framework\Contracts\StrContract;
use Core\Support\HasSingletonTrait;
use Illuminate\Support\Str;

class StrAdapter implements StrContract
{
    use HasSingletonTrait;

    public function random(int $length = 16): string
    {
        return Str::random($length);
    }

    public function title(string $value): string
    {
        return Str::title($value);
    }
}
