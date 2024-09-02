<?php

namespace Core\Domain\Enum\Project;

enum ProjectFileContextEnum: string
{
    case PROJECT = 'PROJECT';
    case TASK = 'TASK';
    case COMMENT = 'COMMENT';
}
