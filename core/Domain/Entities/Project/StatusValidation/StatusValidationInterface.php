<?php

namespace Core\Domain\Entities\Project\StatusValidation;

use Core\Domain\Entities\Project\ProjectEntity;

interface StatusValidationInterface
{
    public function __construct(ProjectEntity $projectEntity);

    public function validate(): bool;

    public function validateWithThrowException(): void;
}