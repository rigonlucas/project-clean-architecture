<?php

namespace Infra\Database\Project\Command;

use App\Models\Project;
use Core\Application\Project\Commons\Gateways\ProjectCommandInterface;
use Core\Domain\Entities\Project\Root\ProjectEntity;
use Exception;

class ProjectCommand implements ProjectCommandInterface
{

    public function create(ProjectEntity $projectEntity): ProjectEntity
    {
        $project = new Project();
        $project->uuid = $projectEntity->getUuid()->toString();
        $project->name = $projectEntity->getName();
        $project->description = $projectEntity->getDescription();
        $project->account_uuid = $projectEntity->getAccount()->getUuid();
        $project->created_by_user_uuid = $projectEntity->getUser()->getUuid();
        $project->status = $projectEntity->getStatus()->value;
        $project->start_at = $projectEntity->getStartAt()?->startOfDay();
        $project->finish_at = $projectEntity->getFinishAt()?->startOfDay();

        $project->save();
        return $projectEntity;
    }

    public function update(ProjectEntity $projectEntity): ProjectEntity
    {
        Project::query()
            ->where('uuid', '=', $projectEntity->getUuid())
            ->update([
                'name' => $projectEntity->getName(),
                'description' => $projectEntity->getDescription(),
                'status' => $projectEntity->getStatus()->value,
                'start_at' => $projectEntity->getStartAt()?->startOfDay(),
                'finish_at' => $projectEntity->getFinishAt()?->startOfDay()
            ]);

        return $projectEntity;
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
