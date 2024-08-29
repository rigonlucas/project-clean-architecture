<?php

namespace Core\Domain\Enum\Project;

enum StatusProjectEnum: string
{
    case BACKLOG = 'BACKLOG';
    case PENDING = 'PENDING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case FINISHED = 'FINISHED';
    case CANCELED = 'CANCELED';
    case VALIDATION = 'VALIDATION';
    case ON_HOLD = 'ON _HOLD';
    case ARCHIVED = 'ARCHIVED';
    case REVIEW = 'REVIEW';
    case DELIVERED = 'DELIVERED';

    public function isNotIn(array $array): bool
    {
        return !in_array($this->value, $array);
    }
}
