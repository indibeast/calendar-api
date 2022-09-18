<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class TokenData extends Data
{
    public function __construct(
        public string $code,
        public ?int $expires_at
    ) {
    }
}
