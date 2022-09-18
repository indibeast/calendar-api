<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CheckUserCredentialsAction
{
    public static function execute($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            return false;
        }

        return $user;
    }
}
