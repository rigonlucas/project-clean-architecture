<?php

namespace Core\Support\Exceptions\Dates;

use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Exception;

class DatesMustBeDifferntsException extends OutputErrorException
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
