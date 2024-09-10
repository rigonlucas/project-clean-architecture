<?php

namespace Infra\Handlers\UseCases\Project\File\Delete;

use Core\Application\Project\File\Delete\ProjectFileDeleteUseCase;
use Core\Application\Project\Shared\Exceptions\ProjectNotFoundException;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectFileMapperInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Domain\Entities\Project\File\ProjectFileEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Exceptions\ErrorOnDeleteFromStorageException;
use Core\Support\Http\ResponseStatus;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\UuidInterface;

readonly class DeleteProjectFileHandler
{
    public function __construct(
        private ProjectMapperInterface $projectMapper,
        private ProjectFileMapperInterface $projectFileMapper,
        private ProjectCommandInterface $projectCommand
    ) {
    }

    /**
     * @throws ForbidenException
     * @throws ProjectNotFoundException
     * @throws ErrorOnDeleteFromStorageException
     */
    public function handler(UuidInterface $fileUuid, UuidInterface $projectUuid, UserEntity $userEntity): void
    {
        $projectFileEntity = $this->forceDeleteFromTable($fileUuid, $projectUuid, $userEntity);
        $this->deleteFileFromStorage($projectFileEntity);
    }

    /**
     * @throws ForbidenException
     * @throws ProjectNotFoundException
     */
    public function forceDeleteFromTable(
        UuidInterface $fileUuid,
        UuidInterface $projectUuid,
        UserEntity $userEntity
    ): ProjectFileEntity {
        $useCase = new ProjectFileDeleteUseCase(
            projectMapper: $this->projectMapper,
            projectFileMapper: $this->projectFileMapper,
            projectCommand: $this->projectCommand
        );
        return $useCase->execute(fileUuid: $fileUuid, projectUuid: $projectUuid, userEntity: $userEntity);
    }

    /**
     * @throws ErrorOnDeleteFromStorageException
     */
    private function deleteFileFromStorage(ProjectFileEntity $projectFileEntity): void
    {
        $wasDeleted = Storage::disk(config('filesystems.default'))->delete($projectFileEntity->getPath());

        if (!$wasDeleted) {
            throw new ErrorOnDeleteFromStorageException(
                'Error on delete file from storage',
                ResponseStatus::INTERNAL_SERVER_ERROR->value
            );
        }
    }
}
