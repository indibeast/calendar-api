<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

test('users can recover their account', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old_password'),
    ]);
    $token = Password::createToken($user);

    $response = $this->json('POST', 'api/account-recovery', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new_password',
        'password_confirmation' => 'new_password',
    ]);

    $response->assertStatus(200);

    $loginResponse = $this->json('POST', 'api/login', [
        'email' => $user->email,
        'password' => 'new_password',
    ]);

    $loginResponse->assertStatus(200);
});

test('users cannot update their account if code is invalid', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old_password'),
    ]);
    $token = Password::createToken($user);

    $response = $this->json('POST', 'api/account-recovery', [
        'token' => 'invalid_code',
        'email' => $user->email,
        'password' => 'new_password',
        'password_confirmation' => 'new_password',
    ]);

    $response->assertStatus(422);

    $loginResponse = $this->json('POST', 'api/login', [
        'email' => $user->email,
        'password' => 'new_password',
    ]);

    $loginResponse->assertStatus(422);
});
