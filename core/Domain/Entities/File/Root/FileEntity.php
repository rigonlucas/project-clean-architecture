<?php

namespace Core\Domain\Entities\File\Root;

use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\File\ContextFileEnum;
use Core\Domain\Enum\File\ExtensionsEnum;
use Core\Domain\Enum\File\StatusFileEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Core\Domain\ValueObjects\File\FilePathValueObjectInterface;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

class FileEntity
{
    use FileEntityBuilder;


    private UuidInterface $uuid;
    private UuidInterface $entityUuid;
    private string $ulidFileName;
    private string $name;
    private FilePathValueObjectInterface $filePathValueObject;
    private TypeFileEnum $type;
    private BytesValueObject $size;
    private ExtensionsEnum $extension;
    private UserEntity $userEntity;
    private ContextFileEnum $context;
    private StatusFileEnum $status;

    private function __construct()
    {
    }

    public function checkProjectFile(): void
    {
        if ($this->size->getBytes() < 1) {
            throw new InvalidArgumentException('File size must be greater than 0 bytes');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPath(): string
    {
        return $this->filePathValueObject->getPath();
    }

    public function getFilePathValueObject(): FilePathValueObjectInterface
    {
        return $this->filePathValueObject;
    }

    public function setFilePathValueObject(FilePathValueObjectInterface $filePathValueObject): void
    {
        $this->filePathValueObject = $filePathValueObject;
    }

    public function getType(): TypeFileEnum
    {
        return $this->type;
    }

    public function setType(TypeFileEnum $type): void
    {
        $this->type = $type;
    }

    public function getSize(): BytesValueObject
    {
        return $this->size;
    }

    public function setSize(BytesValueObject $size): void
    {
        $this->size = $size;
    }

    public function getContext(): ContextFileEnum
    {
        return $this->context;
    }

    public function setContext(ContextFileEnum $context): void
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

    public function getExtension(): ExtensionsEnum
    {
        return $this->extension;
    }

    public function setExtension(ExtensionsEnum $extension): void
    {
        $this->extension = $extension;
    }

    public function getUlidFileName(): string
    {
        return $this->ulidFileName;
    }

    public function setUlidFileName(string $ulidFileName): FileEntity
    {
        $this->ulidFileName = $ulidFileName;
        return $this;
    }

    public function getEntityUuid(): UuidInterface
    {
        return $this->entityUuid;
    }

    public function setEntityUuid(UuidInterface $entityUuid): FileEntity
    {
        $this->entityUuid = $entityUuid;
        return $this;
    }

    public function confirmUpload(): void
    {
        $this->status = StatusFileEnum::FINISHED;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUserEntity(): UserEntity
    {
        return $this->userEntity;
    }

    public function setUserEntity(UserEntity $userEntity): void
    {
        $this->userEntity = $userEntity;
    }

    private function getFilePatValueObject(): FilePathValueObjectInterface
    {
        return $this->filePathValueObject;
    }
}
