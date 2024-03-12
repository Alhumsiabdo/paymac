<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title' => fake()->sentence,
            'content' => fake()->paragraph,
            'excerpt' => fake()->paragraph,
            'user_id' => function () {
                return User::factory()->create()->id; 
            },
        ];
    }
}
