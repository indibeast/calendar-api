<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('users can register', function () {
    expect(User::count())->toBe(0);

    $response = $this->json('POST', '/api/register', [
        'name' => 'john doe',
        'email' => 'test@test.com',
        'password' => 'test1234',
    ]);

    $response->assertStatus(200);

    expect(User::count())->toBe(1);

    tap(User::first(), function ($user) {
        expect($user->name)->toBe('john doe');
        expect($user->email)->toBe('test@test.com');
    });
});

test('name is required when registering', function () {
    expect(User::count())->toBe(0);

    $response = $this->json('POST', '/api/register', [
        'email' => 'test@test.com',
        'password' => 'test1234',
    ]);

    $response->assertStatus(422);

    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.name')->etc());

    expect(User::count())->toBe(0);
});

test('email is required when registering', function () {
    expect(User::count())->toBe(0);

    $response = $this->json('POST', '/api/register', [
        'name' => 'john doe',
        'password' => 'test1234',
    ]);

    $response->assertStatus(422);

    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.email')->etc());

    expect(User::count())->toBe(0);
});

test('email should be unique when registering', function () {
    $userA = User::factory()->create(['email' => 'test@test.com']);

    expect(User::count())->toBe(1);

    $response = $this->json('POST', '/api/register', [
        'name' => 'john doe',
        'email' => 'test@test.com',
        'password' => 'test1234',
    ]);

    $response->assertStatus(422);

    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.email')->etc());

    expect(User::count())->toBe(1);
});

test('password is required when registering', function () {
    expect(User::count())->toBe(0);

    $response = $this->json('POST', '/api/register', [
        'name' => 'john doe',
        'email' => 'test@test.com',
    ]);

    $response->assertStatus(422);

    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.password')->etc());

    expect(User::count())->toBe(0);
});

test('password should at least have 8 characters', function () {
    expect(User::count())->toBe(0);

    $response = $this->json('POST', '/api/register', [
        'name' => 'john doe',
        'email' => 'test@test.com',
        'password' => 'test12',
    ]);

    $response->assertStatus(422);

    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.password')->etc());

    expect(User::count())->toBe(0);
});
