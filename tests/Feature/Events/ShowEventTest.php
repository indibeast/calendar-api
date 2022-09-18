<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('guests cannot see events', function () {
    $user = User::factory()->create();
    $event = Event::factory()->for($user)->create([
        'title' => 'test1',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $response = $this->json('GET', "api/events/{$event->id}");

    $response->assertStatus(401);
});

test('users can view their event', function () {
    $user = User::factory()->create();
    $event = Event::factory()->for($user)->create([
        'title' => 'test1',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $response = $this->actingAs($user)->json('GET', "api/events/{$event->id}");

    $response->assertStatus(200);

    $response->assertJson(fn (AssertableJson $json) => $json->has('result')->etc()
        ->has('result', fn ($json) => $json->where('title', 'test1')
            ->where('start', '2020-04-01T11:00:00+00:00')
            ->where('end', '2020-04-01T12:00:00+00:00')
            ->etc()
        ));
});

test('users cannot view other users event', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $eventAForUserA = Event::factory()->for($userA)->create([
        'title' => 'test1',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $eventBForUserB = Event::factory()->for($userB)->create([
        'title' => 'test2',
        'start' => '2020-04-01T09:00:00+00:00',
        'end' => '2020-04-01T10:00:00+00:00',
    ]);

    $response = $this->actingAs($userA)->json('GET', "api/events/{$eventBForUserB->id}");

    $response->assertStatus(403);
});

test('if event is not found it should throw 404', function () {
    $user = User::factory()->create();
    $event = Event::factory()->for($user)->create([
        'title' => 'test1',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $response = $this->actingAs($user)->json('GET', 'api/events/event-not-in-db');

    $response->assertStatus(404);
});
