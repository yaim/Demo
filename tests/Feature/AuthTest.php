<?php

namespace Tests\Feature;

use App\LoginAttempt;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetHerLastFiveSuccessfulLoginAttempts()
    {
        $user = factory(User::class)->create();

        factory(LoginAttempt::class, 10)->create();
        $attempts = factory(LoginAttempt::class, 5)
            ->create([
                'user_id'    => $user->id,
                'successful' => 1,
            ])
            ->sortByDesc('attempted_at')
            ->map(function ($attempt) {
                return [
                    'attempted_at' => $attempt->attempted_at,
                ];
            })
            ->values()
            ->toArray();

        $response = $this->actingAs($user)
                         ->get('/api/auth/successful-attempts');

        $response->assertStatus(200)
            ->assertJson([
                'data' => $attempts,
            ]);
    }

    public function testUserCannotGetOtherUsersLoginAttempts()
    {
        $user = factory(User::class)->create();

        factory(LoginAttempt::class, 10)->create([
            'successful' => 1,
        ]);

        $response = $this->actingAs($user)
                         ->get('/api/auth/successful-attempts');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
            ]);
    }
}
