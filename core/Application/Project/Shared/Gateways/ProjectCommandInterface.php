<?php

namespace Core\Application\Project\Shared\Gateways;

use Core\Domain\Entities\Project\Root\ProjectEntity;

interface ProjectCommandInterface
{
    public function create(ProjectEntity $projectEntity): ProjectEntity;

    public function update(ProjectEntity $projectEntity): ProjectEntity;

    public function changeStatus(ProjectEntity $projectEntity): ProjectEntity;

    public function delete(ProjectEntity $projectEntity): void;
}