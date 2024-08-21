<?php

namespace Core\Application\Account\Create\Inputs;

readonly class AccountInput
{
    public function __construct(
        public ?string $accessCode
    ) {
    }
}
