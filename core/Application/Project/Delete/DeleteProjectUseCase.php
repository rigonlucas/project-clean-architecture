<?php

namespace Core\Application\Project\Delete;

use Core\Application\Project\Shared\Exceptions\ProjectNotFoundException;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Domain\Aggregates\Project\ProjectDeletionAggregate;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\File\FileStatusEnum;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Http\ResponseStatus;
use Ramsey\Uuid\UuidInterface;

class DeleteProjectUseCase
{
    private ?string $ulidDeletion = null;

    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly ProjectCommandInterface $projectCommand,
        private readonly ProjectMapperInterface $projectMapper
    ) {
        $this->ulidDeletion = $this->framework->uuid()->ulidGenerate();
    }

    /**
     * @throws ForbidenException
     * @throws ProjectNotFoundException
     */
    public function execute(UuidInterface $uuid, UserEntity $userAuth): ProjectDeletionAggregate
    {
        $project = $this->projectMapper->findByUuid($uuid, $userAuth);
        if (!$project) {
            throw new ProjectNotFoundException('Project not found', ResponseStatus::NOT_FOUND->value);
        }
        $project->canDeleteProject());

        $project->setUlidDeletion($this->ulidDeletion);

        $this->projectCommand->deleteProjectCardSoftly($project);
        $this->projectCommand->deleteProjectFileSoftly($project, FileStatusEnum::SOFT_DELETED);
        $this->projectCommand->deleteProjectTaskSoftly($project);
        $this->projectCommand->deleteProjectSoftly($project);

        return new ProjectDeletionAggregate(
            $project,
            $project->getUuid(),
            $project->getUlidDeletion(),
            [
                'project_cards',
                'project_files',
                'project_tasks',
                'projects'
            ]
        );
    }
}
