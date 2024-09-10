<?php

namespace Core\Application\Project\Shared\Gateways;

use Core\Domain\Entities\Project\File\ProjectFileEntity;
use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Enum\File\FileStatusEnum;

interface ProjectCommandInterface
{
    public function create(ProjectEntity $projectEntity): ProjectEntity;

    public function update(ProjectEntity $projectEntity): ProjectEntity;

    public function changeStatus(ProjectEntity $projectEntity): ProjectEntity;

    public function deleteProjectSoftly(ProjectEntity $projectEntity): void;

    public function deleteProjectTaskSoftly(ProjectEntity $projectEntity): void;

    public function deleteProjectCardSoftly(ProjectEntity $projectEntity): void;

    public function deleteProjectFileSoftly(ProjectEntity $projectEntity, FileStatusEnum $fileStatusEnum): void;

    public function deleteProjectFileForce(ProjectFileEntity $projectFileEntity): void;
}