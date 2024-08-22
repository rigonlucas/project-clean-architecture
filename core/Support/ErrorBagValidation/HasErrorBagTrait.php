<?php

namespace Core\Support\ErrorBagValidation;

use Core\Tools\Http\ResponseStatusCodeEnum;

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
        ResponseStatusCodeEnum $errorCodeEnum = ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY
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
