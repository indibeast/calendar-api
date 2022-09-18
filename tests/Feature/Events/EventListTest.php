<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('guests cannot see events', function () {
    $user = User::factory()->create();
    $eventA = Event::factory()->for($user)->create([
        'title' => 'test1',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $response = $this->json('GET', 'api/events');

    $response->assertStatus(401);
});

test('users can see their events', function () {
    $user = User::factory()->create();
    $eventA = Event::factory()->for($user)->create([
        'title' => 'test1',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $eventB = Event::factory()->for($user)->create([
        'title' => 'test2',
        'start' => '2020-04-01T09:00:00+00:00',
        'end' => '2020-04-01T10:00:00+00:00',
    ]);

    $response = $this->actingAs($user)->json('GET', 'api/events');

    $response->assertStatus(200);

    $response->assertJson(fn (AssertableJson $json) => $json->has('result')->etc()
        ->has('result.0', fn ($json) => $json->where('title', 'test1')
            ->where('start', '2020-04-01T11:00:00+00:00')
            ->where('end', '2020-04-01T12:00:00+00:00')
            ->etc()
        )->has('result.1', fn ($json) => $json->where('title', 'test2')
            ->where('start', '2020-04-01T09:00:00+00:00')
            ->where('end', '2020-04-01T10:00:00+00:00')
            ->etc()));
});

test('users can see their events only', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $eventAForUserA = Event::factory()->for($userA)->create([
        'title' => 'test1',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $eventBForUserA = Event::factory()->for($userA)->create([
        'title' => 'test2',
        'start' => '2020-04-01T09:00:00+00:00',
        'end' => '2020-04-01T10:00:00+00:00',
    ]);

    $eventCForUserB = Event::factory()->for($userB)->create([
        'title' => 'test3',
        'start' => '2020-04-01T09:00:00+00:00',
        'end' => '2020-04-01T10:00:00+00:00',
    ]);

    $response = $this->actingAs($userA)->json('GET', 'api/events');

    $response->assertStatus(200);

    $response->assertJson(fn (AssertableJson $json) => $json->has('result', 2)->etc()
        ->has('result.0', fn ($json) => $json->where('title', 'test1')
            ->where('start', '2020-04-01T11:00:00+00:00')
            ->where('end', '2020-04-01T12:00:00+00:00')
            ->etc()
        )->has('result.1', fn ($json) => $json->where('title', 'test2')
            ->where('start', '2020-04-01T09:00:00+00:00')
            ->where('end', '2020-04-01T10:00:00+00:00')
            ->etc()));
});
