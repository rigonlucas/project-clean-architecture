<?php

namespace Infra\Services\Framework;

use Illuminate\Pagination\LengthAwarePaginator;

class DefaultPaginationConverter
{
    public static function convert(
        mixed $collectionBase,
        LengthAwarePaginator $lengthAwarePaginator
    ): mixed {
        return $collectionBase
            ->setCurrentPage($lengthAwarePaginator->currentPage())
            ->setFirstPageUrl($lengthAwarePaginator->url(1))
            ->setFrom($lengthAwarePaginator->firstItem())
            ->setLastPage($lengthAwarePaginator->lastPage())
            ->setLastPageUrl($lengthAwarePaginator->url($lengthAwarePaginator->lastPage()))
            ->setLinks($lengthAwarePaginator->linkCollection()->toArray())
            ->setNextPageUrl($lengthAwarePaginator->nextPageUrl())
            ->setPath($lengthAwarePaginator->path())
            ->setPerPage($lengthAwarePaginator->perPage())
            ->setPrevPageUrl($lengthAwarePaginator->previousPageUrl())
            ->setTo($lengthAwarePaginator->lastItem())
            ->setTotal($lengthAwarePaginator->total());
    }
}
