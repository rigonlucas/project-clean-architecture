<?php

namespace Infra\Database\Project\Command;

use App\Models\Project;
use Core\Application\Project\Commons\Gateways\ProjectCommandInterface;
use Core\Domain\Entities\Project\ProjectEntity;

class ProjectCommand implements ProjectCommandInterface
{

    public function create(ProjectEntity $entity): ProjectEntity
    {
        $project = new Project();
        $project->name = $entity->getName();
        $project->description = $entity->getDescription();
        $project->account_id = $entity->getAccount()->getId();
        $project->created_by_user_id = $entity->getUser()->getId();
        $project->status = null;
        $project->start_at = $entity->getStartAt();
        $project->finish_at = $entity->getFinishAt();
        $project->uuid = $entity->getUuid()->toString();
        
        $project->save();

        $entity->setId($project->id);
        return $entity;
    }
}
