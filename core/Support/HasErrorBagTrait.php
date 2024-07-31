<?php

namespace Core\Support;

trait HasErrorBagTrait
{
    private array $errorBag = [];

    public function addError(string $key, string $message): void
    {
        $this->errorBag[$key][] = $message;
    }

    public function getErrorBag(): array
    {
        return $this->errorBag;
    }

    public function hasErrorBag(): bool
    {
        return !empty($this->errorBag);
    }
}
