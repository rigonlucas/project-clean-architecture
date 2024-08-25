<?php

namespace Core\Application\Project\Commons\Gateways;

use Core\Domain\Entities\Project\ProjectEntity;

interface ProjectCommandInterface
{
    public function create(ProjectEntity $entity): ProjectEntity;
}