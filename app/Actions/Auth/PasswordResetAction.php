<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetAction
{
    public static function execute(array $input)
    {
        return Password::reset(
            $input,
            fn ($user, $password) => $user->update(['password' => Hash::make($password)])
        );
    }
}
