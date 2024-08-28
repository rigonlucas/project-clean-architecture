<?php

namespace Core\Application\Project\Create\inputs;

use Carbon\CarbonInterface;
use Core\Domain\Enum\Project\StatusProjectEnum;

readonly class CreateProjectInput
{
    public function __construct(
        public string $name,
        public string $description,
        public ?CarbonInterface $startAt,
        public ?CarbonInterface $finishAt,
        public StatusProjectEnum $status
    ) {
    }
}
