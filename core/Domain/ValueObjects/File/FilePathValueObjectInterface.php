<?php

namespace Core\Domain\ValueObjects\File;

interface FilePathValueObjectInterface
{
    public function getPath(): string;

    public function apply(): self;

    public function __toString(): string;

    public function addPathSegment(string $segiment): self;
}