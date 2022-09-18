<?php

namespace App\DataTransferObjects;

use Illuminate\Pagination\LengthAwarePaginator;

class ResponsePaginationData extends Data
{
    public function __construct(
        public readonly array $result,
        public readonly PaginationData $meta,
        public readonly bool $status = true
    ) {
    }

    public static function fromPaginator(LengthAwarePaginator $paginator, array $data): self
    {
        return new self(
            result: $data,
            meta: PaginationData::fromPaginator($paginator),
        );
    }
}
