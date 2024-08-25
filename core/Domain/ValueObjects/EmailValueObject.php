<?php

namespace Core\Domain\ValueObjects;

use Core\Support\Exceptions\InvalidEmailException;
use Core\Support\Http\ResponseStatus;

class EmailValueObject
{
    public const string DEFAULT_MASK_SUPPRESSED_EMAIL = '**********@**********.***';
    private string $email;
    private ?string $emailUnsuppressed = null;

    /**
     * @throws InvalidEmailException
     */
    public function __construct(string $email, bool $autoValidete = true)
    {
        $this->email = $email;
        if ($autoValidete && $this->isInvalid()) {
            throw new InvalidEmailException(
                'Invalid email format',
                ResponseStatus::BAD_REQUEST->value
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

    public static function isNotEmailSuppressed(string $email): bool
    {
        return !static::isEmailSuppressed($email);
    }

    public static function isEmailSuppressed(string $email): bool
    {
        return str_contains($email, '*') || $email == '';
    }

    public function isSuppressed(): bool
    {
        return static::isEmailSuppressed($this->email);
    }

    public function isNotSuppressed(): bool
    {
        return !static::isEmailSuppressed($this->email);
    }


    public function supress(): self
    {
        if ($this->email == '') {
            $this->email = static::DEFAULT_MASK_SUPPRESSED_EMAIL;
            return $this;
        }
        $this->emailUnsuppressed = $this->get();
        [$localPart, $domainPart] = explode('@', $this->email);
        $suppress = fn($part) => substr($part, 0, 2) . str_repeat('*', strlen($part) - 2);

        $this->email = $suppress($localPart) . '@' . $suppress($domainPart);
        return $this;
    }

    public function get(): string
    {
        return $this->email;
    }

    public function unsupress(): self
    {
        $this->email = $this->emailUnsuppressed;
        $this->emailUnsuppressed = null;
        return $this;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
