<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('guests cannot create events', function () {
    expect(Event::count())->toBe(0);

    $response = $this->json('POST', 'api/events', [
        'title' => 'test',
        'start' => now()->toIso8601String(),
        'end' => now()->addHour()->toIso8601String(),
    ]);

    $response->assertStatus(401);

    expect(Event::count())->toBe(0);
});

test('authorized users can create events', function () {
    expect(Event::count())->toBe(0);

    $response = $this->actingAs(User::factory()->create())->json('POST', 'api/events', [
        'title' => 'event',
        'start' => now()->toIso8601String(),
        'end' => now()->addHour()->toIso8601String(),
    ]);

    $response->assertStatus(201);

    expect(Event::count())->toBe(1);
});

test('title is required when creating an event', function () {
    expect(Event::count())->toBe(0);

    $response = $this->actingAs(User::factory()->create())->json('POST', 'api/events', [
        'start' => now()->toIso8601String(),
        'end' => now()->addHour()->toIso8601String(),
    ]);

    $response->assertStatus(422);
    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.title')->etc());

    expect(Event::count())->toBe(0);
});

test('start date and time is required when creating an event', function () {
    expect(Event::count())->toBe(0);

    $response = $this->actingAs(User::factory()->create())->json('POST', 'api/events', [
        'title' => 'test',
        'end' => now()->addHour()->toIso8601String(),
    ]);

    $response->assertStatus(422);
    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.start')->etc());

    expect(Event::count())->toBe(0);
});

test('end date and time is required when creating an event', function () {
    expect(Event::count())->toBe(0);

    $response = $this->actingAs(User::factory()->create())->json('POST', 'api/events', [
        'title' => 'test',
        'start' => now()->toIso8601String(),
    ]);

    $response->assertStatus(422);
    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.end')->etc());

    expect(Event::count())->toBe(0);
});

test('end date should be greater than start date', function () {
    expect(Event::count())->toBe(0);

    $response = $this->actingAs(User::factory()->create())->json('POST', 'api/events', [
        'title' => 'test',
        'start' => now()->toIso8601String(),
        'end' => now()->subHour()->toIso8601String(),
    ]);

    $response->assertStatus(422);
    $response->assertJson(fn (AssertableJson $json) => $json->has('errors.end')->etc());

    expect(Event::count())->toBe(0);
});

test('events cannot be overlapped', function ($start, $end) {
    $user = User::factory()->create();
    Event::factory()->for($user)->create([
        'start' => '2020-04-01T11:00:00+00:00',
        'end' => '2020-04-01T12:00:00+00:00',
    ]);

    $response = $this->actingAs($user)->json('POST', 'api/events', [
        'title' => 'test',
        'start' => $start,
        'end' => $end,
    ]);

    $response->assertStatus(422);
})->with([
    ['2020-04-01T11:30:00+00:00', '2020-04-01T12:30:00+00:00'], // start date is within existing event dates
    ['2020-04-01T11:30:00+00:00', '2020-04-01T11:35:00+00:00'], // start date and end date is within existing event dates
    ['2020-04-01T10:30:00+00:00', '2020-04-01T11:30:00+00:00'], // end date is within existing event dates
    ['2020-04-01T11:00:00+00:00', '2020-04-01T12:00:00+00:00'], // same dates as existing event
    ['2020-04-01T10:00:00+00:00', '2020-04-01T11:00:00+00:00'], // end time is equal to existing event's start time
]);
