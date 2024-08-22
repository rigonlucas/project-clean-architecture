<?php

namespace Core\Domain\ValueObjects;

use Core\Generics\Exceptions\InvalidEmailException;
use Core\Tools\Http\ResponseStatusCodeEnum;

class EmailValueObject
{
    private string $email;
    private string $emailUnsuppressed = '';

    /**
     * @throws InvalidEmailException
     */
    public function __construct(string $email, bool $autoValidete = true)
    {
        $this->email = $email;
        if ($autoValidete && $this->isInvalid()) {
            throw new InvalidEmailException(
                'Invalid email format',
                ResponseStatusCodeEnum::BAD_REQUEST->value
            );
        }
    }

    public function isInvalid(): bool
    {
        return !$this->isValid();
    }

    public function isValid(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function supress(): self
    {
        $this->emailUnsuppressed = $this->getEmail();
        [$localPart, $domainPart] = explode('@', $this->email);
        $suppress = fn($part) => substr($part, 0, 2) . str_repeat('*', strlen($part) - 2);

        $this->email = $suppress($localPart) . '@' . $suppress($domainPart);
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function unsupress(): self
    {
        $this->email = $this->emailUnsuppressed;
        $this->emailUnsuppressed = '';
        return $this;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
