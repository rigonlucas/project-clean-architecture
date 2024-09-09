<?php

namespace Core\Application\Project\Upload;

use Core\Application\File\Shared\Gateways\FileCommandInterface;
use Core\Application\Project\Shared\Exceptions\ProjectNotFoundException;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Application\Shared\Inputs\FiletInput;
use Core\Domain\Entities\File\Root\FileEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Http\ResponseStatus;

readonly class ProjectUploadFileUseCase
{
    public function __construct(
        private FrameworkContract $framework,
        private FileCommandInterface $fileCommand,
        private ProjectMapperInterface $projectMapper
    ) {
    }

    /**
     * @throws ForbidenException
     * @throws ProjectNotFoundException
     */
    public function execute(FiletInput $projecFiletInput, UserEntity $authUserEntity): FileEntity
    {
        $projectEntity = $this->projectMapper->findByUuid($projecFiletInput->uuid, $authUserEntity);
        if (!$projectEntity) {
            throw new ProjectNotFoundException(
                'Project not found',
                ResponseStatus::NOT_FOUND->value
            );
        }
        $projectEntity->canChangeProject();

        $projectFileEntity = FileEntity::forCreate(
            uuid: $this->framework->uuid()->uuid7Generate(),
            entityUuid: $projectEntity->getUuid(),
            name: $projecFiletInput->name,
            type: $projecFiletInput->type,
            size: $projecFiletInput->size,
            extension: $projecFiletInput->extension,
            userEntity: $authUserEntity,
            context: $projecFiletInput->contextFile
        );

        return $this->fileCommand->create($projectFileEntity, $projectEntity->getUuid());
    }
}
