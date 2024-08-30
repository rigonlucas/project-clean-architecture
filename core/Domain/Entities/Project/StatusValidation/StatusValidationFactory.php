<?php

namespace Core\Domain\Entities\Project\StatusValidation;

use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\Project\StatusValidation\Strategies\DefaultValidation;
use Core\Domain\Entities\Project\StatusValidation\Strategies\InProgressValidation;
use Core\Domain\Enum\Project\StatusProjectEnum;

class StatusValidationFactory
{
    public static function make(ProjectEntity $projectEntity): StatusValidationInterface
    {
        return match ($projectEntity->getStatus()) {
            StatusProjectEnum::IN_PROGRESS => new InProgressValidation($projectEntity),
            default => new DefaultValidation($projectEntity),
        };
    }
}
