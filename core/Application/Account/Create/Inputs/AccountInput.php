<?php

namespace Core\Application\Account\Create\Inputs;

use Ramsey\Uuid\UuidInterface;

class AccountInput
{
    private ?UuidInterface $userUuid = null;
    private ?string $userName;
    private int $id;

    public function __construct(
        public readonly ?string $accessCode
    ) {
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userNane): void
    {
        $this->userName = $userNane;
    }

    public function getUserUuid(): UuidInterface
    {
        return $this->userUuid;
    }

    public function setUserUuid(UuidInterface $userUuid): void
    {
        $this->userUuid = $userUuid;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
