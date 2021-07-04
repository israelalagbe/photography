<?php

namespace Tests\Feature;

use App\Models\ProductRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProductRequestTest extends TestCase
{
    public function testGetProductRequests(): void
    {

        $user = User::factory()->create();
        $token = Auth::login($user);

        ProductRequest::factory()->count(10)->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->get('api/product_requests');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        [
                            'title',
                            'description',
                            'reference_code',
                            'photographer_id',
                            'client_id',
                            'status'
                        ]
                    ]
                ]
            ]);
    }

    public function testGetAcceptedProductRequests(): void
    {

        $user = User::factory()->create([
            'role' => 'photographer'
        ]);
        $token = Auth::login($user);

        ProductRequest::factory()->count(2)->create(['photographer_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->get('api/product_requests/accepted');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        [
                            'title',
                            'description',
                            'reference_code',
                            'photographer_id',
                            'client_id',
                            'status'
                        ]
                    ]
                ]
            ]);
    }

    public function testGetClientProductRequests(): void
    {

        $user = User::factory()->create([
            'role' => 'client'
        ]);
        $token = Auth::login($user);

        ProductRequest::factory()->count(2)->create(['client_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->get('api/product_requests/client');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        [
                            'title',
                            'description',
                            'reference_code',
                            'photographer_id',
                            'client_id',
                            'status'
                        ]
                    ]
                ]
            ]);
    }

    public function testAcceptProductRequests(): void
    {

        $user = User::factory()->create([
            'role' => 'photographer'
        ]);

        $token = Auth::login($user);

        ProductRequest::factory()->count(2)->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->post('api/product_requests/accept', [
            'id' => 1
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [

                    'title',
                    'description',
                    'reference_code',
                    'photographer_id',
                    'client_id',
                    'status'

                ]
            ]);
    }

    public function testStoreProductRequests(): void
    {

        $user = User::factory()->create([
            'role' => 'client'
        ]);

        $token = Auth::login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->post('api/product_requests', [
            'title' => "Apple Picture",
            'description' => "Take it from all angles"
        ]);


        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [

                    'title',
                    'description',
                    'reference_code',
                    'client_id',
                    'status'
                ]
            ]);
    }
}
