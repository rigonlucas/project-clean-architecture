<?php

namespace Core\Application\Project\Commons\Gateways;

use Core\Domain\Entities\Project\ProjectEntity;

interface ProjectCommandInterface
{
    public function create(ProjectEntity $projectEntity): ProjectEntity;

    public function update(ProjectEntity $projectEntity): ProjectEntity;

    public function changeStatus(ProjectEntity $projectEntity): ProjectEntity;

    public function delete(ProjectEntity $projectEntity): void;
}