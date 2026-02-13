<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->paragraph(2),
            'slug' => $this->faker->slug(10, true),
            'imgs' => 'favicon_game.ico',
            'price' => $this->faker->randomDigit(),
            'quantity' => $this->faker->numberBetween(0, 300),
            'blog_id' => $this->faker->numberBetween(1, 50),
            'type_id' => $this->faker->numberBetween(1, 100),
            'purchase_price' => $this->faker->randomDigit(),
            'sale_price' => $this->faker->randomDigit(),
            'taxes_porcent' => $this->faker->numberBetween(0, 100),
        ];
    }
}
