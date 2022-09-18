<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data as SpatieData;
use Spatie\LaravelData\Lazy;

class ResponseData extends Data
{
    public function __construct(
        public readonly ?SpatieData $result,
        public readonly bool $status = true,
        public array $errors = [],
        public Lazy|string $message = ''
    ) {
    }
}
