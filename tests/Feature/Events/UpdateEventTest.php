<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('guests cannot update calendar events', function () {
    $user = User::factory()->create();
    $event = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $response = $this->json('PUT', "api/events/{$event->id}", [
        'title' => 'update',
        'start' => '2021-04-01T11:00:00+00:00',
        'end' => '2021-04-01T11:00:00+00:00',
    ]);

    $response->assertStatus(401);

    tap($event->fresh(), function ($event) {
        expect($event->title)->toBe('test');
        expect($event->start)->toBe('2020-04-01 11:00:00');
        expect($event->end)->toBe('2020-04-01 12:00:00');
    });
});

test('authorised users can update calendar events', function () {
    $user = User::factory()->create();
    $event = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $response = $this->actingAs($user)->json('PUT', "api/events/{$event->id}", [
        'title' => 'update',
        'start' => '2021-04-01T11:00:00+00:00',
        'end' => '2021-04-01T12:00:00+00:00',
    ]);

    $response->assertStatus(200);

    tap($event->fresh(), function ($event) {
        expect($event->title)->toBe('update');
        expect($event->start)->toBe('2021-04-01 11:00:00');
        expect($event->end)->toBe('2021-04-01 12:00:00');
    });
});

test('user cannot update an event which belongs to another user', function () {
    $user = User::factory()->create();
    $event = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $response = $this->actingAs(User::factory()->create())->json('PUT', "api/events/{$event->id}", [
        'title' => 'update',
        'start' => '2021-04-01T11:00:00+00:00',
        'end' => '2021-04-01T12:00:00+00:00',
    ]);

    $response->assertStatus(403);

    tap($event->fresh(), function ($event) {
        expect($event->title)->toBe('test');
        expect($event->start)->toBe('2020-04-01 11:00:00');
        expect($event->end)->toBe('2020-04-01 12:00:00');
    });
});

test('user cannot update an event if it is overlapping with another event', function ($start, $end) {
    $user = User::factory()->create();
    $eventA = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $eventB = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T09:00:00+00:00',
        'end' => '2020-04-01T10:00:00+00:00',
    ]);

    $response = $this->actingAs($user)->json('PUT', "api/events/{$eventB->id}", [
        'title' => 'update',
        'start' => $start,
        'end' => $end,
    ]);

    $response->assertStatus(422);
    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.end')->etc());

    tap($eventB->fresh(), function ($event) {
        expect($event->title)->toBe('test');
        expect($event->start)->toBe('2020-04-01 09:00:00');
        expect($event->end)->toBe('2020-04-01 10:00:00');
    });
})->with([
    ['2020-04-01T11:30:00+00:00', '2020-04-01T12:30:00+00:00'], // start date is within existing event dates
    ['2020-04-01T11:30:00+00:00', '2020-04-01T11:35:00+00:00'], // start date and end date is within existing event dates
    ['2020-04-01T10:30:00+00:00', '2020-04-01T11:30:00+00:00'], // end date is within existing event dates
    ['2020-04-01T11:00:00+00:00', '2020-04-01T12:00:00+00:00'], // same dates as existing event
    ['2020-04-01T10:00:00+00:00', '2020-04-01T11:00:00+00:00'], // end time is equal to existing event's start time
]);

test('title is required when updating an event', function () {
    $user = User::factory()->create();

    $eventA = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T09:00:00+00:00',
        'end' => '2020-04-01T10:00:00+00:00',
    ]);

    $response = $this->actingAs($user)->json('PUT', "api/events/{$eventA->id}", [
        'start' => '2021-04-01T11:00:00+00:00',
        'end' => '2021-04-01T12:00:00+00:00',
    ]);

    $response->assertStatus(422);
    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.title')->etc());
});

test('start date and time is required when updating an event', function () {
    $user = User::factory()->create();

    $eventA = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T09:00:00+00:00',
        'end' => '2020-04-01T10:00:00+00:00',
    ]);

    $response = $this->actingAs($user)->json('PUT', "api/events/{$eventA->id}", [
        'title' => 'test',
        'end' => '2021-04-01T12:00:00+00:00',
    ]);

    $response->assertStatus(422);
    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.start')->etc());
});

test('end date and time is required when updating an event', function () {
    $user = User::factory()->create();

    $eventA = Event::factory()->for($user)->create([
        'title' => 'test',
        'start' => '2020-04-01T09:00:00+00:00',
        'end' => '2020-04-01T10:00:00+00:00',
    ]);

    $response = $this->actingAs($user)->json('PUT', "api/events/{$eventA->id}", [
        'title' => 'test',
        'start' => '2020-04-01T09:00:00+00:00',
    ]);

    $response->assertStatus(422);
    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.end')->etc());
});
