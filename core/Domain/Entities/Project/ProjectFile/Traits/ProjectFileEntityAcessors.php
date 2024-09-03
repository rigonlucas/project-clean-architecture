<?php

namespace Core\Domain\Entities\Project\ProjectFile\Traits;

use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\File\AllowedExtensionsEnum;
use Core\Domain\Enum\File\StatusFileEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\Enum\Project\ProjectFileContextEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Ramsey\Uuid\UuidInterface;

trait ProjectFileEntityAcessors
{
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getType(): TypeFileEnum
    {
        return $this->type;
    }

    public function getSize(): BytesValueObject
    {
        return $this->size;
    }

    public function getProjectEntity(): ProjectEntity
    {
        return $this->projectEntity;
    }

    public function getUserEntity(): UserEntity
    {
        return $this->userEntity;
    }


    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function setType(TypeFileEnum $type): void
    {
        $this->type = $type;
    }

    public function setSize(BytesValueObject $size): void
    {
        $this->size = $size;
    }

    public function setProjectEntity(ProjectEntity $projectEntity): void
    {
        $this->projectEntity = $projectEntity;
    }

    public function setUserEntity(UserEntity $userEntity): void
    {
        $this->userEntity = $userEntity;
    }

    public function getContext(): ProjectFileContextEnum
    {
        return $this->context;
    }

    public function setContext(ProjectFileContextEnum $context): void
    {
        $this->context = $context;
    }

    public function getStatus(): StatusFileEnum
    {
        return $this->status;
    }

    public function setStatus(StatusFileEnum $status): void
    {
        $this->status = $status;
    }

    public function getExtension(): AllowedExtensionsEnum
    {
        return $this->extension;
    }

    public function setExtension(AllowedExtensionsEnum $extension): void
    {
        $this->extension = $extension;
    }

}