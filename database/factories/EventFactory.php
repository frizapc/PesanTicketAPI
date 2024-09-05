<?php

namespace Database\Factories;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title"=> fake()->word(),
            "description" => fake()->text(50),
            "location" => fake()->city(),
            "start_time" => fake()->dateTime('now'),
            "end_time" => fake()->dateTime('now'),
            "organizer_id" => Str::random(26)
        ];
    }
}
