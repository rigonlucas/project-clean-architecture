<?php

namespace Core\Domain\ValueObjects\File;

use InvalidArgumentException;

class DefaultPathValueObject implements FilePathValueObjectInterface
{
    /**
     * This mask is used to generate the path of the file in the storage
     * @example {ACCOUNT_UUID}/{CONTEXT}/{PROJECT_UUID}/{FILE_UUID}.{EXTENSION}
     */
    private const string FILE_PATH_MASK = '%s/%s/%s/%s.%s';
    private array $segiments = [];
    private ?string $path = null;

    public function apply(): self
    {
        if (count($this->segiments) !== substr_count(self::FILE_PATH_MASK, '%s')) {
            throw new InvalidArgumentException('Path is already full');
        }

        $this->path = sprintf(
            self::FILE_PATH_MASK,
            ...$this->segiments,
        );

        return $this;
    }

    public function __toString(): string
    {
        return $this->getPath();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function addPathSegment(string $segiment): DefaultPathValueObject
    {
        $this->segiments[] = $segiment;
        return $this;
    }
}
