<?php

namespace Core\Application\Shared\Inputs;

use Core\Domain\Enum\File\FileContextEnum;
use Core\Domain\Enum\File\FileExtensionsEnum;
use Core\Domain\Enum\File\FileTypeEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Ramsey\Uuid\UuidInterface;

readonly class FiletInput
{

    public function __construct(
        public string $name,
        public FileTypeEnum $type,
        public BytesValueObject $size,
        public FileExtensionsEnum $extension,
        public FileContextEnum $contextFile,
        public UuidInterface $uuid
    ) {
    }
}
