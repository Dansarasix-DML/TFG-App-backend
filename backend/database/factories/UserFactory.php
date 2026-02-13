<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    private function generate_token() {
        $rb = random_bytes(32);
        $token = base64_encode($rb);
        $secureToken = uniqid('', true).$token;
        return urlencode($secureToken); # Maximizamos compatibilidad para pasar el token por URL a través del email
    }

    


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $uname = $this->faker->username();

        return [
            'name' => $this->faker->name(),
            'username' => $uname,
            'bio' => $this->faker->paragraph(2),
            'avatar' => 'avatar.png',
            'banner' => 'banner.png',
            'telephone' => $this->faker->numberBetween(600000000, 699999999),
            'email' => $this->faker->email(),
            'password' => Hash::make($uname),
            'active' => $this->faker->boolean(),
            'token' => $this->generate_token()
        ];
    }
}
