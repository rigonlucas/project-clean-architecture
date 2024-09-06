<?php

namespace Core\Domain\Entities\File\Root;

use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\File\ContextFileEnum;
use Core\Domain\Enum\File\ExtensionsEnum;
use Core\Domain\Enum\File\StatusFileEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Core\Domain\ValueObjects\File\DefaultPathValueObject;
use Ramsey\Uuid\UuidInterface;

trait FileEntityBuilder
{
    public static function forCreate(
        UuidInterface $uuid,
        string $name,
        TypeFileEnum $type,
        BytesValueObject $size,
        ExtensionsEnum $extension,
        UserEntity $userEntity,
        ContextFileEnum $context
    ): FileEntity {
        $projectFileEntity = new FileEntity();
        $projectFileEntity->setUuid($uuid);
        $projectFileEntity->setName($name);
        $projectFileEntity->setType($type);
        $projectFileEntity->setSize($size);
        $projectFileEntity->setExtension($extension);
        $projectFileEntity->setUserEntity($userEntity);
        $projectFileEntity->setContext($context);
        $projectFileEntity->setStatus(StatusFileEnum::PENDING);
        $projectFileEntity->setPath(new DefaultPathValueObject());

        $projectFileEntity->checkProjectFile();
        $projectFileEntity->applyPathMask();

        return $projectFileEntity;
    }
}