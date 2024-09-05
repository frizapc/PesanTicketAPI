<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        for ($i=0; $i < 5; $i++) { 
            $user = User::factory()->create();
            for($x=0; $x < 2; $x++){
                $event = Event::factory()->for($user, 'organizer')->create();
                Ticket::factory()
                    ->for($user, 'user')
                    ->for($event, 'event')
                    ->create();
            }
        }
    }
}
