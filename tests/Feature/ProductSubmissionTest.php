<?php

namespace Tests\Feature;

use App\Models\ProductRequest;
use App\Models\ProductSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    public function testProductSubmission(): void
    {

        $user = User::factory()->create([
            'role' => 'photographer'
        ]);

        ProductRequest::factory()->create();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('file.png', 600, 600);

        $token = Auth::login($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->post('api/product_requests/1/submissions', [
            'image' => $file,
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'image',
                    'thumbnail',
                    'product_request_id',
                    'status',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseCount('product_requests', 1);
    }
}
