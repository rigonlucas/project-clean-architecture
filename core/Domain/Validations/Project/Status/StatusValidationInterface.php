<?php

namespace Core\Domain\Validations\Project\Status;

use Core\Domain\Entities\Project\Root\ProjectEntity;

interface StatusValidationInterface
{
    public function __construct(ProjectEntity $projectEntity);

    public function validate(): void;
}