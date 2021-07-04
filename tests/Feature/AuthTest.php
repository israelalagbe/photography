<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $user = User::factory()->create();

        $response = $this->post('api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => $user->email]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegister()
    {

        $response = $this->post('api/auth/register', [
            'name' => "John Doe",
            'email' => "test@example.com",
            'password' => 'password',
            'role' => 'client'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => "test@example.com"]);
    }
}
