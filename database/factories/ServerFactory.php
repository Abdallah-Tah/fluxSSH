<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Server>
 */
class ServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->words(2, true),
            'host' => fake()->domainName(),
            'port' => 22,
            'username' => fake()->userName(),
            'auth_type' => fake()->randomElement(['password', 'key']),
            'password' => fake()->password(),
            'private_key' => null,
            'is_active' => true,
            'connection_options' => [],
            'last_connected_at' => null,
        ];
    }

    /**
     * Indicate the server uses key authentication.
     */
    public function keyAuth(): static
    {
        return $this->state(fn (array $attributes) => [
            'auth_type' => 'key',
            'password' => null,
            'private_key' => fake()->text(1000),
        ]);
    }

    /**
     * Indicate the server uses password authentication.
     */
    public function passwordAuth(): static
    {
        return $this->state(fn (array $attributes) => [
            'auth_type' => 'password',
            'private_key' => null,
            'password' => fake()->password(),
        ]);
    }

    /**
     * Indicate the server is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
