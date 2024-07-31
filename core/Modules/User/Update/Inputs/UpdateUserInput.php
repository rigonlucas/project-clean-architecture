<?php

namespace Core\Modules\User\Update\Inputs;

use DateTimeInterface;
use SensitiveParameter;

readonly class UpdateUserInput
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        #[SensitiveParameter]
        public string $password,
        public DateTimeInterface $birthday
    ) {
    }
}
