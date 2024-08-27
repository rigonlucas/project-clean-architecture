<?php

namespace Core\Support\Exceptions\Access;

use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Exception;

class ForbidenException extends OutputErrorException
{
    public function __construct(
        string $message,
        int $code = ResponseStatus::FORBIDDEN->value,
        Exception $previous = null,
        ?array $errors = []
    ) {
        parent::__construct($message, $code, $previous, $errors);
    }
}
