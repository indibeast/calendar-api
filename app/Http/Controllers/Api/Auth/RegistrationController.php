<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\CreateNewUserAction;
use App\DataTransferObjects\ResponseData;
use App\DataTransferObjects\UserData;
use App\Http\Requests\RegistrationRequest;

class RegistrationController
{
    public function __invoke(RegistrationRequest $request)
    {
        $user = UserData::from(CreateNewUserAction::execute($request->all()));

        return ResponseData::from(['result' => $user]);
    }
}
