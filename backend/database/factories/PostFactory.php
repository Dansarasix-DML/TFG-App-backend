<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
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
            'subtitle' => $this->faker->sentence(12, true),
            'slug' => $this->faker->slug(10, true),
            'banner_img' => 'banner002.png',
            'summary' => $this->faker->paragraph(1, true),
            'content' => $this->faker->paragraph(10, true),
            'blog_id' => $this->faker->numberBetween(1, 50),
        ];
    }
}
