<?php

namespace Core\Domain\Enum\File;

enum StatusFileEnum: string
{
    case PENDING = 'PENDING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case FINISHED = 'FINISHED';
    case CANCELED = 'CANCELED';
}
