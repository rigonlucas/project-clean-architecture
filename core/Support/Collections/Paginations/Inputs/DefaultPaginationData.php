<?php

namespace Core\Support\Collections\Paginations\Inputs;

use InvalidArgumentException;

readonly class DefaultPaginationData
{
    public int $page;
    public int $perPage;

    public function __construct(
        int $page = 1,
        int $perPage = 10
    ) {
        if ($perPage > 50) {
            throw new InvalidArgumentException('The maximum number of items per page is 50');
        }

        $this->setPage($page);

        $this->setPerPage($perPage);
    }

    private function setPage(int $page): void
    {
        if ($page < 1) {
            $this->page = 1;
            return;
        }
        $this->page = $page;
    }

    private function setPerPage(int $perPage): void
    {
        if ($perPage < 1) {
            $this->perPage = 10;
            return;
        }

        $this->perPage = $perPage;
    }
}
