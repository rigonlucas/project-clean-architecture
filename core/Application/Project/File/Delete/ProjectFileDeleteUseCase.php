<?php

namespace Core\Application\Project\File\Delete;

use Core\Application\Project\Shared\Exceptions\ProjectNotFoundException;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectFileMapperInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Domain\Entities\Project\File\ProjectFileEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Http\ResponseStatus;
use Ramsey\Uuid\UuidInterface;

readonly class ProjectFileDeleteUseCase
{
    public function __construct(
        private ProjectMapperInterface $projectMapper,
        private ProjectFileMapperInterface $projectFileMapper,
        private ProjectCommandInterface $projectCommand
    ) {
    }

    /**
     * @throws ProjectNotFoundException
     * @throws ForbidenException
     */
    public function execute(
        UuidInterface $fileUuid,
        UuidInterface $projectUuid,
        UserEntity $userEntity
    ): ProjectFileEntity {
        $projectEntity = $this->projectMapper->findByUuid($projectUuid, $userEntity);
        if (!$projectEntity) {
            throw new ProjectNotFoundException('Project not found', ResponseStatus::NOT_FOUND->value);
        }

        $projectFileEntity = $this->projectFileMapper->findByUuid($fileUuid, $projectUuid);
        if (!$projectFileEntity) {
            throw new ProjectNotFoundException('Project file not found', ResponseStatus::NOT_FOUND->value);
        }

        $projectEntity->canDeleteProject();
        $this->projectCommand->deleteProjectFileForce($projectFileEntity);
        
        return $projectFileEntity;
    }
}
