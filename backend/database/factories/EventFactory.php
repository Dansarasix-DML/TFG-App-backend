<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'blog_id' => $this->faker->numberBetween(1, 49),
            'title' => $this->faker->sentence(16, true),
            'subtitle' => $this->faker->sentence(10, true),
            'slug' => $this->faker->slug(10, true),
            'banner_img' => 'banner_img.png',
            'content' => $this->faker->sentence(40, true),
            'start_dtime' => $this->faker->dateTime(),
            'end_dtime' => $this->faker->dateTime(),
            'ubication' => $this->faker->address(),
            'section' => 'Sección '.$this->faker->numberBetween(1, 300),
            'capacity' => $this->faker->numberBetween(1, 5000),
        ];
    }
}
