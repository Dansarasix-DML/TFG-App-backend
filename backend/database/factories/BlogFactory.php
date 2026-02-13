<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
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
            'title' => $this->faker->sentence(16, true),
            'slug' => $this->faker->slug(10, true),
            'description' => $this->faker->paragraph(),
            'blogger' => $this->faker->numberBetween(1, 99),
            'profile_img' => 'avatar01.png',
            'banner_img' => 'banner01.jpg',
        ];
    }
}
