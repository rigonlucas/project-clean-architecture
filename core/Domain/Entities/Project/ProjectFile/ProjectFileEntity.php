<?php

namespace Core\Domain\Entities\Project\ProjectFile;

use Core\Domain\Entities\Project\ProjectEntity;
use Core\Domain\Entities\Project\ProjectFile\Traits\ProjectFileEntityAcessors;
use Core\Domain\Entities\Project\ProjectFile\Traits\ProjectFileEntityBuilder;
use Core\Domain\Entities\User\UserEntity;
use Core\Domain\Enum\File\AllowedExtensionsEnum;
use Core\Domain\Enum\File\StatusFileEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\Enum\Project\ProjectFileContextEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

class ProjectFileEntity
{
    use ProjectFileEntityBuilder;
    use ProjectFileEntityAcessors;

    /**
     * This mask is used to generate the path of the file in the storage
     * @example {ACCOUNT_UUID}/{CONTEXT}/{FILE_UUID}.{EXTENSION}
     */
    private const string FILE_PATH_MASK = '%s/%s/%s.%s';

    private UuidInterface $uuid;
    private string $name;
    private string $path = '';
    private TypeFileEnum $type;
    private BytesValueObject $size;
    private AllowedExtensionsEnum $extension;
    private ProjectEntity $projectEntity;
    private UserEntity $userEntity;
    private ProjectFileContextEnum $context;
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

    public function applyPathMask(): void
    {
        $this->path = sprintf(
            self::FILE_PATH_MASK,
            $this->userEntity->getAccount()->getUuid(),
            strtolower($this->context->value),
            $this->uuid->toString(),
            $this->extension->value
        );
    }
}
