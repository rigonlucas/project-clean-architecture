<?php

namespace Core\Application\Project\Commons\Exceptions;

use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;

class ProjectStatusUnableException extends OutputErrorException
{
    public function __construct(
        string $message,
        int $code = ResponseStatus::UNPROCESSABLE_ENTITY->value,
        array $errors = []
    ) {
        parent::__construct($message, $code, null, $errors);
    }
}
