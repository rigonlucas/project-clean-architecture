<?php

namespace Infra\Database\Project\Command;

use App\Models\Project;
use App\Models\ProjectCard;
use App\Models\ProjectFile;
use App\Models\ProjectTask;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Domain\Entities\Project\File\ProjectFileEntity;
use Core\Domain\Entities\Project\Root\ProjectEntity;
use Core\Domain\Enum\File\FileStatusEnum;
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

    public function changeStatus(ProjectEntity $projectEntity): ProjectEntity
    {
        throw new Exception('Not implemented yet');
    }

    public function deleteProjectTaskSoftly(ProjectEntity $projectEntity): void
    {
        ProjectTask::query()
            ->where('project_uuid', '=', $projectEntity->getUuid())
            ->update([
                'deleted_at' => now(),
                'ulid_deletion' => $projectEntity->getUlidDeletion(),
            ]);
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

    public function deleteProjectSoftly(ProjectEntity $projectEntity): void
    {
        Project::query()
            ->where('uuid', '=', $projectEntity->getUuid())
            ->update([
                'deleted_at' => now(),
                'ulid_deletion' => $projectEntity->getUlidDeletion(),
            ]);
    }

    public function deleteProjectCardSoftly(ProjectEntity $projectEntity): void
    {
        ProjectCard::query()
            ->where('project_uuid', '=', $projectEntity->getUuid())
            ->update([
                'deleted_at' => now(),
                'ulid_deletion' => $projectEntity->getUlidDeletion(),
            ]);
    }

    public function deleteProjectFileSoftly(ProjectEntity $projectEntity, FileStatusEnum $fileStatusEnum): void
    {
        ProjectFile::query()
            ->where('project_uuid', '=', $projectEntity->getUuid())
            ->update([
                'status' => $fileStatusEnum->value,
                'deleted_at' => now(),
                'ulid_deletion' => $projectEntity->getUlidDeletion(),
            ]);
    }

    public function deleteProjectFileForce(ProjectFileEntity $projectFileEntity): void
    {
        ProjectFile::query()
            ->where('uuid', '=', $projectFileEntity->getUuid())
            ->where('project_uuid', '=', $projectFileEntity->getProjectUuid())
            ->forceDelete();
    }
}
