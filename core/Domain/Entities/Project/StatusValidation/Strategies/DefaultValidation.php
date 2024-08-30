<?php

namespace Core\Domain\Entities\Project\StatusValidation\Strategies;

use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\Project\StatusValidation\StatusValidationInterface;

readonly class DefaultValidation implements StatusValidationInterface
{
    public function __construct(ProjectEntity $projectEntity)
    {
    }

    public function validate(): bool
    {
        return true;
    }

    public function validateWithThrowException(): void
    {
    }
}
