<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->withToken('kf4G6Sxra1XV6yjcR6sZ4waHTvAuHhVswW5elRQgbe3159b7')->getJson('/api/user');
        $response
            ->assertJson(fn (AssertableJson $json)=>
            $json
            ->has('user', 4)
            ->has('user.user_events', fn(AssertableJson $json)=>
                $json->first(fn(AssertableJson $json)=>
                    $json->where('title', 'getoainergr')->etc()  
                )
            )
            ->etc()
        );
        // dd(Str::after('http://localhost:8000/storage/qrcodes/5747018601j0q5bpr2r38ywbgqesxnveht.png', 'qrcodes/'));
    }
}
