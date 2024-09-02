<?php

namespace Core\Domain\Enum\File;

use ReflectionClass;

enum AllowedFileTypesEnum: string
{
    // Documentos
    case CSV = 'csv'; // Comma-separated values
    case XLS = 'xls'; // Excel 97-2003
    case XLSX = 'xlsx'; // Excel 2007
    case PDF = 'pdf'; // Portable Document Format
    case DOC = 'doc'; // Word 97-2003
    case DOCX = 'docx'; // Word 2007
    case TXT = 'txt'; // Texto
    case RTF = 'rtf'; // Rich Text Format
    case ODT = 'odt'; // Open Document Text
    case ODS = 'ods'; // Open Document Spreadsheet
    case ODP = 'odp'; // Open Document Presentation
    case PPT = 'ppt'; // PowerPoint 97-2003
    case PPTX = 'pptx'; // PowerPoint 2007
    case MD = 'md'; // Markdown (Readme.md)

    // Imagens
    case JPG = 'jpg'; // JPEG
    case JPEG = 'jpeg'; // JPEG
    case PNG = 'png'; // Portable Network Graphics
    case GIF = 'gif'; // Graphics Interchange Format
    case BMP = 'bmp'; // Bitmap
    case SVG = 'svg'; // Scalable Vector Graphics
    case TIFF = 'tiff'; // Tagged Image File Format
    case ICO = 'ico'; // Icon
    case WEBP = 'webp'; // WebP

    // Arquivos Compactados
    case ZIP = 'zip'; // Zip
    case RAR = 'rar'; // Rar
    case SEVEN_Z = '7z'; // 7-Zip
    case TAR = 'tar'; // Tar
    case GZ = 'gz'; // Gzip

    public static function getFullTypes(): array
    {
        $reflection = new ReflectionClass(self::class);
        $constants = $reflection->getConstants();

        return array_values($constants);
    }

    public static function getOnlyTypes(): array
    {
        $reflection = new ReflectionClass(self::class);
        $constants = $reflection->getConstants();

        return array_keys($constants);
    }

}

