<?php

namespace Infra\Database\Project\Command;

use App\Models\ProjectFile;
use Core\Application\Project\Commons\Gateways\ProjectFileCommandInterface;
use Core\Domain\Entities\Project\ProjectFile\ProjectFileEntity;

class ProjectFileCommand implements ProjectFileCommandInterface
{
    public function create(ProjectFileEntity $projectFileEntity): ProjectFileEntity
    {
        $projectFile = new ProjectFile();
        $projectFile->uuid = $projectFileEntity->getUuid();
        $projectFile->name = $projectFileEntity->getName();
        $projectFile->path = $projectFileEntity->getPath();
        $projectFile->type = $projectFileEntity->getType()->value;
        $projectFile->size = $projectFileEntity->getSize()->getBytes();
        $projectFile->extension = $projectFileEntity->getExtension();
        $projectFile->project_id = $projectFileEntity->getProjectEntity()->getUuid();
        $projectFile->user_id = $projectFileEntity->getUserEntity()->getUuid();
        $projectFile->account_uuid = $projectFileEntity->getUserEntity()->getAccount()->getUuid();
        $projectFile->context = $projectFileEntity->getContext()->value;
        $projectFile->status = $projectFileEntity->getStatus()->value;
        $projectFile->save();

        return $projectFileEntity;
    }
}
