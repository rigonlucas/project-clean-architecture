<?php

namespace Core\Application\Project\Commons\Gateways;

use Core\Domain\Entities\Project\ProjectFile\ProjectFileEntity;

interface ProjectFileCommandInterface
{
    public function create(ProjectFileEntity $projectFileEntity): ProjectFileEntity;
}