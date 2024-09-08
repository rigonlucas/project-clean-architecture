<?php

namespace Core\Domain\Enum\File;

enum FileStatusEnum: string
{
    case AVAILABLE = 'AVAILABLE';
    case CHECKING = 'CHECKING';
    case SOFT_DELETED = 'SOFT_DELETED';
    case ACHIVED = 'ACHIVED';
}
