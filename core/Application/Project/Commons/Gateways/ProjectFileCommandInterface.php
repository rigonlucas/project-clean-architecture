<?php

namespace Core\Application\Project\Commons\Gateways;

use Core\Domain\Entities\File\Root\FileEntity;
use Core\Domain\Entities\Project\Root\ProjectEntity;

interface ProjectFileCommandInterface
{
    public function create(FileEntity $projectFileEntity, ProjectEntity $projectEntity): FileEntity;
}