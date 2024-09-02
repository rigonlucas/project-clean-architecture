<?php

namespace Core\Domain\Enum\File;

enum TypeFileEnum: string
{
    case IMAGE = 'IMAGE';
    case VIDEO = 'VIDEO';
    case AUDIO = 'AUDIO';
    case DOCUMENT = 'DOCUMENT';
    case OTHER = 'OTHER';
}
