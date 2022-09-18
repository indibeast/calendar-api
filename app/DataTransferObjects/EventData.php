<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;

class EventData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public string $title,
        public Carbon $start,
        public Carbon $end,
    ) {
    }
}
