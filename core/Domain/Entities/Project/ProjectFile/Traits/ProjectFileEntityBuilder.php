<?php

namespace Core\Domain\Entities\Project\ProjectFile\Traits;

use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\Project\ProjectFile\ProjectFileEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\File\AllowedExtensionsEnum;
use Core\Domain\Enum\File\StatusFileEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\Enum\Project\ProjectFileContextEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Ramsey\Uuid\UuidInterface;

trait ProjectFileEntityBuilder
{
    public static function forCreate(
        UuidInterface $uuid,
        string $name,
        TypeFileEnum $type,
        BytesValueObject $size,
        AllowedExtensionsEnum $extension,
        ProjectEntity $projectEntity,
        UserEntity $userEntity,
        ProjectFileContextEnum $context
    ): ProjectFileEntity {
        $projectFileEntity = new ProjectFileEntity();
        $projectFileEntity->setUuid($uuid);
        $projectFileEntity->setName($name);
        $projectFileEntity->setType($type);
        $projectFileEntity->setSize($size);
        $projectFileEntity->setExtension($extension);
        $projectFileEntity->setProjectEntity($projectEntity);
        $projectFileEntity->setUserEntity($userEntity);
        $projectFileEntity->setContext($context);
        $projectFileEntity->setStatus(StatusFileEnum::PENDING);

        $projectFileEntity->checkProjectFile();
        $projectFileEntity->applyPathMask();

        return $projectFileEntity;
    }
}