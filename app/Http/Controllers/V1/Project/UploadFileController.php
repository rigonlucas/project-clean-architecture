<?php

namespace App\Http\Controllers\V1\Project;

use App\Http\Controllers\Controller;
use Core\Application\File\Gateways\FileCommandInterface;
use Core\Application\Project\Commons\Gateways\ProjectMapperInterface;
use Core\Services\Framework\FrameworkContract;

class UploadFileController extends Controller
{
    public function __construct(
        private readonly FrameworkContract $contract,
        private readonly FileCommandInterface $fileCommand,
        private readonly ProjectMapperInterface $projectMapper
    ) {
    }

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
    }
}
