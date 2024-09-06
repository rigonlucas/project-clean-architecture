<?php

namespace Core\Application\Project\Upload;

use Core\Application\Project\Commons\Exceptions\ProjectNotFoundException;
use Core\Application\Project\Commons\Gateways\ProjectFileCommandInterface;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Application\Project\Upload\inputs\ProjecFiletInput;
use Core\Domain\Entities\File\Root\FileEntity;
use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Exceptions\Access\ForbidenException;
use Core\Support\Http\ResponseStatus;

class ProjectUploadFileUseCase
{
    public function __construct(
        private FrameworkContract $framework,
        private ProjectFileCommandInterface $fileCommand,
        private ProjectMapperInterface $projectMapper
    ) {
    }

    /**
     * @throws ForbidenException
     * @throws ProjectNotFoundException
     */
    public function execute(ProjecFiletInput $projecFiletInput, UserEntity $authUserEntity): FileEntity
    {
        $projectEntity = $this->projectMapper->findByUuid($projecFiletInput->projectUuid, $authUserEntity);
        if ($projectEntity) {
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
            context: $projecFiletInput->context
        );

        return $this->fileCommand->create($projectFileEntity, $projectEntity);
    }
}
