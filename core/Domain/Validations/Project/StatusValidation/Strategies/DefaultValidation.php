<?php

namespace Core\Domain\Validations\Project\StatusValidation\Strategies;

use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Validations\Project\StatusValidation\StatusValidationInterface;

readonly class DefaultValidation implements StatusValidationInterface
{
    public function __construct(ProjectEntity $projectEntity)
    {
    }

    public function validate(): void
    {
    }
}
