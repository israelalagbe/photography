<?php

namespace Tests\Feature;

use App\Models\ProductSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProductSubmissionTest extends TestCase
{
    public function testGetProductSubmissions(): void
    {

        $user = User::factory()->create(['role' => 'client']);
        $token = Auth::login($user);

        ProductSubmission::factory()->create(['id' => 1]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->get('api/product_requests/1/submissions');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([

                'data' => [
                    [
                        'id',
                        'thumbnail',
                        'product_request_id',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
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
