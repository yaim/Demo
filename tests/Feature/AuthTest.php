<?php

namespace Tests\Feature;

use App\LoginAttempt;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $basePath = '/api/auth';

    public function testGuestCanRegisterAndGetAuthToken()
    {
        $response = $this->post($this->basePath.'/register', [
            'name'     => 'Jane Doe',
            'email'    => 'test@example.com',
            'password' => 'secure',
        ]);

        $this->assertJWTResponse($response);
    }

    protected function assertJWTResponse($response)
    {
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                ],
            ]);

        $token = json_decode($response->getContent())->data->access_token;

        $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->get($this->basePath.'/user')
            ->assertStatus(200);
    }

    public function testLoggedInUserCannotRegisterAgain()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post($this->basePath.'/register', [
                'name'     => 'Jane Doe',
                'email'    => 'test@example.com',
                'password' => 'secure',
            ])
            ->assertStatus(302);
    }

    public function testLoggedInUserCannotLoginAgain()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->post($this->basePath.'/login', [
                'email'    => 'test@example.com',
                'password' => 'secure',
            ])
            ->assertStatus(302);
    }

    public function testGuestCanLoginAndGetAuthToken()
    {
        $user = factory(User::class)->create([
            'email'    => 'test@example.com',
            'password' => Hash::make('secure'),
        ]);

        $response = $this->post($this->basePath.'/login', [
            'email'    => 'test@example.com',
            'password' => 'secure',
        ]);

        $this->assertJWTResponse($response);
    }

    public function testUserCanGetHerBasicInfo()
    {
        $user = factory(User::class)->create([
            'name'  => 'Jane Doe',
            'email' => 'jane.d@example.com',
        ]);

        $this->actingAs($user)
            ->get($this->basePath.'/user')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ])
            ->assertJson([
                'data' => [
                    'name'  => 'Jane Doe',
                    'email' => 'jane.d@example.com',
                ],
            ]);
    }

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
            ->get($this->basePath.'/successful-attempts');

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
            ->get($this->basePath.'/successful-attempts');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
            ]);
    }
}
