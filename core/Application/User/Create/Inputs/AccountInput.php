<?php

namespace Core\Application\User\Create\Inputs;

readonly class AccountInput
{
    public function __construct(
        public ?string $name,
        public ?string $accessCode
    ) {
    }
}
