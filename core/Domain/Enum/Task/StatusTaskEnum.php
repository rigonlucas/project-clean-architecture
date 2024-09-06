<?php

namespace Core\Domain\Enum\Task;

enum StatusTaskEnum: string
{
    case PENDING = 'PENDING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case DONE = 'DONE';
    case CANCELED = 'CANCELED';
    case FAILED = 'FAILED';
    case ARCHIVED = 'ARCHIVED';
    case STOPPED = 'STOPPED';
}
