<?php

namespace Core\Domain\Entities\Project\File;

use Ramsey\Uuid\UuidInterface;

trait HasProjectFileBuilder
{
    public static function forDelete(
        UuidInterface $uuid,
        UuidInterface $projectUuid,
        string $path
    ): ProjectFileEntity {
        $projectFileEntity = new ProjectFileEntity();
        $projectFileEntity->setUuid($uuid);
        $projectFileEntity->setProjectUuid($projectUuid);
        $projectFileEntity->setPath($path);

        return $projectFileEntity;
    }
}