<?php

namespace Core\Domain\Validations\Project\StatusValidation\Strategies;

use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Validations\Project\StatusValidation\StatusValidationInterface;
use Core\Support\Exceptions\Dates\DateNotAllowedException;
use Core\Support\Exceptions\Dates\DateRequiredException;

readonly class InProgressValidation implements StatusValidationInterface
{
    public function __construct(private ProjectEntity $projectEntity)
    {
    }

    /**
     * @throws DateRequiredException
     * @throws DateNotAllowedException
     */
    public function validate(): void
    {
        if (is_null($this->projectEntity->getStartAt())) {
            throw new DateRequiredException('Project must have a start date when status is in progress');
        }

        if (!is_null($this->projectEntity->getFinishAt())) {
            throw new DateNotAllowedException('Project must not have a finish date when status is in progress');
        }
    }
}
