<?php

namespace Core\Domain\Enum\File;

enum FileContextEnum: int
{
    case PROJECT = 1;
    case TASK = 2;
    case COMMENT = 3;
}
