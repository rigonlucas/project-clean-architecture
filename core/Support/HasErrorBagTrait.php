<?php

namespace Core\Support;

use Core\Generics\Exceptions\OutputErrorException;
use Core\Tools\Http\ResponseStatusCodeEnum;

trait HasErrorBagTrait
{
    private array $errorBag = [];

    public function addError(string $key, string $message): void
    {
        $this->errorBag[$key][] = $message;
    }

    /**
     * @throws OutputErrorException
     */
    public function hasErrorBag(
        string $message = 'Validation error',
        ResponseStatusCodeEnum $errorCodeEnum = ResponseStatusCodeEnum::UNPROCESSABLE_ENTITY
    ): void {
        if (!empty($this->errorBag)) {
            throw new OutputErrorException(
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
