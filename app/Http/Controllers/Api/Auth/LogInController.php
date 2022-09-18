<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\CheckUserCredentialsAction;
use App\DataTransferObjects\LoginData;
use App\DataTransferObjects\ResponseData;
use App\DataTransferObjects\TokenData;
use App\DataTransferObjects\UserData;
use App\Http\Requests\LoginRequest;
use Illuminate\Validation\ValidationException;

class LogInController
{
    public function __invoke(LoginRequest $request)
    {
        $user = CheckUserCredentialsAction::execute($request->email, $request->password);

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $token = $user->createToken('access_token');

        $loginData = LoginData::from([
            'user' => UserData::from($user),
            'token' => TokenData::from(['code' => $token->plainTextToken, 'expires_at' => $token->accessToken->expires_at]),
        ]);

        return ResponseData::from(['result' => $loginData]);
    }
}
