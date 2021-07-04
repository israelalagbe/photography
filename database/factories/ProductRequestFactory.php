<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\ProductRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->text(),
            'client_id' => User::factory(),
            'photographer_id' => User::factory(),
            'reference_code' => Str::random(10),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'completed'])
        ];
    }
}
