<?php

namespace Database\Factories;

use App\Models\ProductRequest;
use App\Models\ProductSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductSubmissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductSubmission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image' => $this->faker->imageUrl(),
            'thumbnail' => $this->faker->imageUrl(),
            'product_request_id' => ProductRequest::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected'])
        ];
    }
}
