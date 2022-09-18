<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'start' => now()->toIso8601String(),
            'end' => now()->addHour()->toIso8601String(),
            'user_id' => User::factory(),
        ];
    }
}
