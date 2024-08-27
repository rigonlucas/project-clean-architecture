<?php

namespace Core\Support\Collections\Paginations\Simple;

use Core\Support\Exceptions\MethodMustBeImplementedException;
use Exception;

trait HasDefaultPagination
{
    private int $currentPage = 1;
    private ?string $firstPageUrl = null;
    private ?int $from = null;
    private int $lastPage = 1;
    private ?string $lastPageUrl = null;
    private array $links = [];
    private ?string $nextPageUrl = null;
    private ?string $path = null;
    private int $perPage = 15;
    private ?string $prevPageUrl = null;
    private ?int $to = null;
    private int $total = 0;

    /**
     * @throws MethodMustBeImplementedException
     * @throws Exception
     */
    public function paginated(): array
    {
        if (!method_exists($this, 'toArray')) {
            throw new MethodMustBeImplementedException('Method toArray() not found in class ' . get_class($this));
        }

        return [
            'current_page' => $this->getCurrentPage(),
            'data' => $this->toArray(),
            'first_page_url' => $this->getFirstPageUrl(),
            'from' => $this->getFrom(),
            'last_page' => $this->getLastPage(),
            'last_page_url' => $this->getLastPageUrl(),
            'links' => $this->getLinks(),
            'next_page_url' => $this->getNextPageUrl(),
            'path' => $this->getPath(),
            'per_page' => $this->getPerPage(),
            'prev_page_url' => $this->getPrevPageUrl(),
            'to' => $this->getTo(),
            'total' => $this->getTotal(),
        ];
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getFirstPageUrl(): ?string
    {
        return $this->firstPageUrl;
    }

    public function setFirstPageUrl(?string $firstPageUrl): self
    {
        $this->firstPageUrl = $firstPageUrl;
        return $this;
    }

    public function getFrom(): ?int
    {
        return $this->from;
    }

    public function setFrom(?int $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function setLastPage(int $lastPage): self
    {
        $this->lastPage = $lastPage;
        return $this;
    }

    public function getLastPageUrl(): ?string
    {
        return $this->lastPageUrl;
    }

    public function setLastPageUrl(?string $lastPageUrl): self
    {
        $this->lastPageUrl = $lastPageUrl;
        return $this;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function setLinks(array $links): self
    {
        $this->links = $links;
        return $this;
    }

    public function getNextPageUrl(): ?string
    {
        return $this->nextPageUrl;
    }

    public function setNextPageUrl(?string $nextPageUrl): self
    {
        $this->nextPageUrl = $nextPageUrl;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function getPrevPageUrl(): ?string
    {
        return $this->prevPageUrl;
    }

    public function setPrevPageUrl(?string $prevPageUrl): self
    {
        $this->prevPageUrl = $prevPageUrl;
        return $this;
    }

    public function getTo(): ?int
    {
        return $this->to;
    }

    public function setTo(?int $to): self
    {
        $this->to = $to;
        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }

}
