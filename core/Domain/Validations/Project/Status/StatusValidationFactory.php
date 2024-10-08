<?php

namespace Core\Domain\Validations\Project\Status;

use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Core\Domain\Validations\Project\Status\Strategies\DefaultValidation;
use Core\Domain\Validations\Project\Status\Strategies\InProgressValidation;
use Core\Domain\Validations\Project\Status\Strategies\PendingValidation;

class StatusValidationFactory
{
    public static function make(ProjectEntity $projectEntity): StatusValidationInterface
    {
        return match ($projectEntity->getStatus()) {
            StatusProjectEnum::IN_PROGRESS => new InProgressValidation($projectEntity),
            StatusProjectEnum::PENDING => new PendingValidation($projectEntity),
            default => new DefaultValidation($projectEntity),
        };
    }
}
