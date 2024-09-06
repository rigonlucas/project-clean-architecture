<?php

namespace Core\Domain\ValueObjects\File;

use Core\Domain\Enum\File\ContextFileEnum;
use Ramsey\Uuid\UuidInterface;

class DefaultPathValueObject implements FilePathValueObjectInterface
{
    /**
     * This mask is used to generate the path of the file in the storage
     * @example {ACCOUNT_UUID}/{CONTEXT}/{FILE_UUID}.{EXTENSION}
     */
    private const string FILE_PATH_MASK = '%s/%s/%s.%s';
    private ?string $path = null;

    public function getPath(): string
    {
        return $this->path;
    }

    public function apply(
        UuidInterface $accountUuid,
        ContextFileEnum $contextEnum,
        string $fileName,
        string $fileExtension
    ): self {
        $this->path = sprintf(
            self::FILE_PATH_MASK,
            $accountUuid->toString(),
            $contextEnum->value,
            $fileName,
            $fileExtension
        );

        return $this;
    }

    public function __toString(): string
    {
        return $this->path;
    }
}
