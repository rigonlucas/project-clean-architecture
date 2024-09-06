<?php

namespace Core\Application\Project\Upload;

use Core\Application\Common\Inputs\ProjecFiletInput;
use Core\Application\File\Gateways\FileCommandInterface;
use Core\Application\Project\Commons\Exceptions\ProjectNotFoundException;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Domain\Entities\File\Root\FileEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Http\ResponseStatus;

class ProjectUploadFileUseCase
{
    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly FileCommandInterface $fileCommand,
        private readonly ProjectMapperInterface $projectMapper
    ) {
    }

    /**
     * @throws ForbidenException
     * @throws ProjectNotFoundException
     */
    public function execute(ProjecFiletInput $projecFiletInput, UserEntity $authUserEntity): FileEntity
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
