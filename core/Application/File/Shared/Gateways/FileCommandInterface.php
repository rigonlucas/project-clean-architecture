<?php

namespace Core\Application\File\Shared\Gateways;

use Core\Domain\Entities\File\Root\FileEntity;
use Ramsey\Uuid\UuidInterface;

interface FileCommandInterface
{
    public function create(FileEntity $projectFileEntity, UuidInterface $referenciaUuid): FileEntity;

    public function confirmUploadFile(FileEntity $fileEntity): void;
}