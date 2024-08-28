<?php

namespace Infra\Database\Project\Command;

use App\Models\Project;
use Core\Application\Project\Commons\Gateways\ProjectCommandInterface;
use Core\Domain\Entities\Project\ProjectEntity;
use Exception;

class ProjectCommand implements ProjectCommandInterface
{

    public function create(ProjectEntity $projectEntity): ProjectEntity
    {
        $project = new Project();
        $project->name = $projectEntity->getName();
        $project->description = $projectEntity->getDescription();
        $project->account_id = $projectEntity->getAccount()->getId();
        $project->created_by_user_id = $projectEntity->getUser()->getId();
        $project->status = null;
        $project->start_at = $projectEntity->getStartAt();
        $project->finish_at = $projectEntity->getFinishAt();
        $project->uuid = $projectEntity->getUuid()->toString();

        $project->save();

        $projectEntity->setId($project->id);
        return $projectEntity;
    }

    public function update(ProjectEntity $projectEntity): ProjectEntity
    {
        throw new Exception('Not implemented yet');
    }

    public function changeStatus(ProjectEntity $projectEntity): ProjectEntity
    {
        throw new Exception('Not implemented yet');
    }

    public function delete(ProjectEntity $projectEntity): void
    {
        throw new Exception('Not implemented yet');
    }
}
