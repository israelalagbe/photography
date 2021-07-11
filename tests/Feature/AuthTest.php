<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{

    public function testLogin(): void
    {
        $user = User::factory()->create();

        $response = $this->post('api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function testRegister(): void
    {

        $response = $this->post('api/auth/register', [
            'name' => "John Doe",
            'email' => "test@example.com",
            'password' => 'password',
            'role' => 'client'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => "John Doe",
            'email' => "test@example.com",
            'role' => 'client'
        ]);
    }

    public function testProfile(): void
    {
        $user = User::factory()->create();
        $token = Auth::login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->get('api/auth/profile');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'role',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }
}
