<?php

namespace Core\Application\Account\Create\Inputs;

use Ramsey\Uuid\UuidInterface;

class AccountInput
{
    private ?UuidInterface $userUuid = null;
    private ?string $userNane;

    public function __construct(
        public readonly ?string $accessCode
    ) {
    }

    public function getUserNane(): ?string
    {
        return $this->userNane;
    }

    public function setUserNane(string $userNane): void
    {
        $this->userNane = $userNane;
    }

    public function getUserUuid(): UuidInterface
    {
        return $this->userUuid;
    }

    public function setUserUuid(UuidInterface $userUuid): void
    {
        $this->userUuid = $userUuid;
    }

}
