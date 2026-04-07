<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Street>
 */
class StreetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $zones = ['Zone A', 'Zone B', 'Zone C', 'Zone D'];

        return [
            'name' => fake()->streetName(),
            'zone' => fake()->randomElement($zones),
            'description' => fake()->sentence(),
        ];
    }
}
