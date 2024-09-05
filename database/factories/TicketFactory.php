<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id'=> fake()->numerify(),
            'user_id'=> Str::random(26),
            'ticket_code'=> Str::random(6),
        ];
    }

    // public function suspended(): Factory {
    //     return $this->state(function (array $attributes){
    //         return [
    //             'status' => 'Teregistrasi',
    //         ];
    //     });
    // }
}
