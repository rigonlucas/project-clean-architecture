<?php

namespace Core\Domain\Entities\File\Root;

use Core\Domain\Entities\Shared\User\Root\UserEntity;
use Core\Domain\Enum\File\ContextFileEnum;
use Core\Domain\Enum\File\ExtensionsEnum;
use Core\Domain\Enum\File\StatusFileEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Core\Domain\ValueObjects\File\DefaultPathValueObject;
use Core\Domain\ValueObjects\File\FilePathValueObjectInterface;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Uid\Ulid;

class FileEntity
{
    use FileEntityBuilder;


    private UuidInterface $uuid;
    private string $ulidFileName;
    private string $name;
    private FilePathValueObjectInterface $path;
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
        return $this->path->getPath();
    }

    public function setPath(DefaultPathValueObject $path): void
    {
        $this->path = $path;
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

    private function applyPathMask(): void
    {
        $this->path->apply(
            $this->getUserEntity()->getAccount()->getUuid(),
            $this->context,
            $this->ulidFileName = Ulid::generate(),
            $this->extension->value
        );
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
}
