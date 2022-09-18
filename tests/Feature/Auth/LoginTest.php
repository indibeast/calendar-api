<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('users can log in with correct credentials', function () {
    $userA = User::factory()->password('test1234')->create([
        'email' => 'john@test.com',
    ]);

    $response = $this->json('POST', 'api/login', [
        'email' => 'john@test.com',
        'password' => 'test1234',
    ]);

    $response->assertStatus(200);

    $response->assertJson(fn (AssertableJson $json) => $json
        ->has('result.user.name')
        ->has('result.token.code')
        ->where('result.user.email', 'john@test.com')
        ->etc()
    );
});

test('email is required when login', function () {
    $userA = User::factory()->password('test1234')->create([
        'email' => 'john@test.com',
    ]);

    $response = $this->json('POST', 'api/login', [
        'password' => 'test1234',
    ]);

    $response->assertStatus(422);

    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.email')->etc());
});

test('password is required when login', function () {
    $userA = User::factory()->password('test1234')->create([
        'email' => 'john@test.com',
    ]);

    $response = $this->json('POST', 'api/login', [
        'email' => 'john@test.com',
    ]);

    $response->assertStatus(422);
    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.password')->etc());
});

test('it should not allow to login if password is incorrect', function () {
    $userA = User::factory()->password('test1234')->create([
        'email' => 'john@test.com',
    ]);

    $response = $this->json('POST', 'api/login', [
        'email' => 'john@test.com',
        'password' => 'incorrect',
    ]);

    $response->assertStatus(422);

    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.email')->etc());
});
