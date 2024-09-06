<?php

namespace Core\Domain\Enum\File;

enum ContextFileEnum: string
{
    case PROJECT = 'project';
    case PROJECT_TASK = 'project_task';
    case PROJECT_COMMENT = 'project_comment';
}
