<?php

namespace Core\Application\Account\Create\Inputs;

class AccountInput
{
    private ?int $userId;
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

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

}
