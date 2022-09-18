<?php

namespace App\DataTransferObjects;

use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public function __construct(
        public UserData $user,
        public TokenData $token,
    ) {
    }
}
