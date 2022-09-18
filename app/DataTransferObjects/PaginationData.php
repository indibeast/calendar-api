<?php

namespace App\DataTransferObjects;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;

class PaginationData extends Data
{
    public function __construct(
        public readonly int $currentPage,
        public readonly int $lastPage,
        public readonly string $path,
        public readonly int $perPage,
        public readonly int $total,
    ) {
    }

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return new self(
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage(),
            path: $paginator->path(),
            perPage: $paginator->perPage(),
            total: $paginator->total()
        );
    }
}
