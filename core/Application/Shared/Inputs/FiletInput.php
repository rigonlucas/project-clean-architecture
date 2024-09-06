<?php

namespace Core\Application\Shared\Inputs;

use Core\Domain\Enum\File\ContextFileEnum;
use Core\Domain\Enum\File\ExtensionsEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Ramsey\Uuid\UuidInterface;

readonly class FiletInput
{

    public function __construct(
        public string $name,
        public TypeFileEnum $type,
        public BytesValueObject $size,
        public ExtensionsEnum $extension,
        public ContextFileEnum $contextFile,
        public UuidInterface $uuid
    ) {
    }
}
