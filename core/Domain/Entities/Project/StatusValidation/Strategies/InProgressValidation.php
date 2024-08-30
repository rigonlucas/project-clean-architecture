<?php

namespace Core\Domain\Entities\Project\StatusValidation\Strategies;

use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\Project\StatusValidation\StatusValidationInterface;
use Core\Support\Exceptions\Dates\DateRequiredException;

readonly class InProgressValidation implements StatusValidationInterface
{
    public function __construct(private ProjectEntity $projectEntity)
    {
    }

    public function validate(): bool
    {
        if (is_null($this->projectEntity->getStartAt())) {
            return false;
        }

        return true;
    }

    /**
     * @throws DateRequiredException
     */
    public function validateWithThrowException(): void
    {
        if (is_null($this->projectEntity->getStartAt())) {
            throw new DateRequiredException('Project must have a start date when status is in progress');
        }
    }
}
