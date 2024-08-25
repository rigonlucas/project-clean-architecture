<?php

namespace Core\Application\Project\Create\inputs;

use Carbon\CarbonInterface;

readonly class CreateProjectInput
{
    public function __construct(
        public string $name,
        public string $description,
        public ?CarbonInterface $startAt,
        public ?CarbonInterface $finishAt
    ) {
    }
}
