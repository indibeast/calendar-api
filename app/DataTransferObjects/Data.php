<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data as SpatieData;
use Spatie\LaravelData\Lazy;

class Data extends SpatieData
{
    public Lazy|int $httpCode = 200;

    public function toResponse($request)
    {
        $response = parent::toResponse($request);

        $response->setStatusCode($this->httpCode);

        return $response;
    }
}
