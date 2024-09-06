<?php

namespace Core\Domain\ValueObjects\File;

use Core\Domain\Enum\File\ContextFileEnum;
use Ramsey\Uuid\UuidInterface;

interface FilePathValueObjectInterface
{
    public function getPath(): string;

    public function apply(
        UuidInterface $accountUuid,
        ContextFileEnum $contextEnum,
        UuidInterface $entityUuid,
        string $fileName,
        string $fileExtension
    ): self;

    public function __toString(): string;
}