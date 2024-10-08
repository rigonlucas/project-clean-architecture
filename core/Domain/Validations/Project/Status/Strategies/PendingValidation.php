<?php

namespace Core\Domain\Validations\Project\Status\Strategies;

use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Validations\Project\Status\StatusValidationInterface;
use Core\Support\Exceptions\Dates\DateNotAllowedException;

readonly class PendingValidation implements StatusValidationInterface
{
    public function __construct(private ProjectEntity $projectEntity)
    {
    }

    /**
     * @throws DateNotAllowedException
     */
    public function validate(): void
    {
        if ($this->projectEntity->getFinishAt() !== null) {
            throw new DateNotAllowedException('Project must not have a finish date when status is pending');
        }
    }
}
