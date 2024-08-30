<?php

namespace Core\Application\Project\Update\inputs;

use Carbon\CarbonInterface;
use Core\Domain\Enum\Project\StatusProjectEnum;
use Ramsey\Uuid\UuidInterface;

readonly class UpdateProjectInput
{
    public function __construct(
        public UuidInterface $uuid,
        public string $name,
        public string $description,
        public ?CarbonInterface $startAt,
        public ?CarbonInterface $finishAt,
        public StatusProjectEnum $status
    ) {
    }
}
