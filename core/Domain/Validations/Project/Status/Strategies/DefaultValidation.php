<?php

namespace Core\Domain\Validations\Project\Status\Strategies;

use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Validations\Project\Status\StatusValidationInterface;

readonly class DefaultValidation implements StatusValidationInterface
{
    public function __construct(ProjectEntity $projectEntity)
    {
    }

    public function validate(): void
    {
    }
}
