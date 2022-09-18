<?php

use App\Models\Event;
use App\Models\User;

test('guests cannot delete event', function () {
    $user = User::factory()->create();
    $event = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $response = $this->json('DELETE', "api/events/{$event->id}");

    $response->assertStatus(401);
});

test('users can delete events', function () {
    $user = User::factory()->create();
    $event = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    expect(Event::count())->toBe(1);

    $response = $this->actingAs($user)->json('DELETE', "api/events/{$event->id}");

    $response->assertStatus(204);

    expect(Event::count())->toBe(0);
});

test('users cannot delete other users events', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $eventAForUserA = Event::factory()->for($userA)->create([
        'title' => 'test1',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);
    $eventBForUserB = Event::factory()->for($userB)->create([
        'title' => 'test1',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    expect(Event::count())->toBe(2);

    $response = $this->actingAs($userA)->json('DELETE', "api/events/{$eventBForUserB->id}");

    $response->assertStatus(403);

    expect(Event::count())->toBe(2);
});
