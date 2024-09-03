<?php

namespace Core\Application\Project\Upload\inputs;

use Core\Domain\Enum\File\AllowedExtensionsEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\Enum\Project\ProjectFileContextEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Ramsey\Uuid\UuidInterface;

readonly class ProjecFiletInput
{

    public function __construct(
        public string $name,
        public TypeFileEnum $type,
        public BytesValueObject $size,
        public AllowedExtensionsEnum $extension,
        public ProjectFileContextEnum $context,
        public UuidInterface $projectUuid
    ) {
    }

}
