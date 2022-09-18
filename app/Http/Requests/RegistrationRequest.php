<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

class RegistrationRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', Password::min(8)],
        ];
    }
}
