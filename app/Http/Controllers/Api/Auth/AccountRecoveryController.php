<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\PasswordResetAction;
use App\DataTransferObjects\ResponseData;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;

class AccountRecoveryController extends Controller
{
    public function __invoke(PasswordResetRequest $request)
    {
        $status = PasswordResetAction::execute($request->all());

        if ($status === Password::PASSWORD_RESET) {
            return ResponseData::from(['message' => 'Password Reset Successfully'])->only('message', 'status');
        }

        return ResponseData::from(['status' => false, 'errors' => [__($status)], 'httpCode' => Response::HTTP_UNPROCESSABLE_ENTITY])->only('errors', 'status');
    }
}
