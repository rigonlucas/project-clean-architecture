<?php

namespace Core\Support\Validations;

use Core\Support\Exceptions\InvalideRules\HasErrorsInBagException;
use Core\Support\Http\ResponseStatus;

trait HasErrorBagTrait
{
    private array $errorBag = [];

    protected function addError(string $key, string $message): void
    {
        $this->errorBag[$key][] = $message;
    }

    /**
     * @throws HasErrorsInBagException
     */
    protected function checkValidationErrors(
        string $message = 'Validation error',
        ResponseStatus $errorCodeEnum = ResponseStatus::UNPROCESSABLE_ENTITY
    ): void {
        if (!empty($this->errorBag)) {
            throw new HasErrorsInBagException(
                message: $message,
                code: $errorCodeEnum->value,
                errors: $this->getErrorBag()
            );
        }
    }

    public function getErrorBag(): array
    {
        return $this->errorBag;
    }
}
