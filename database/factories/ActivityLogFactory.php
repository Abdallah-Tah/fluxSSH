<?php

namespace Database\Factories;

use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['connection', 'command', 'error', 'config_change'];
        $type = fake()->randomElement($types);

        $actions = [
            'connection' => ['connected', 'disconnected'],
            'command' => ['executed', 'success'],
            'error' => ['failed', 'error'],
            'config_change' => ['updated', 'created', 'deleted'],
        ];

        return [
            'user_id' => User::factory(),
            'server_id' => Server::factory(),
            'type' => $type,
            'action' => fake()->randomElement($actions[$type]),
            'description' => fake()->sentence(),
            'metadata' => [
                'key1' => fake()->word(),
                'key2' => fake()->numberBetween(1, 100),
            ],
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    public function connectionType(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'connection',
            'action' => fake()->randomElement(['connected', 'disconnected']),
            'description' => 'Connected to server',
        ]);
    }

    public function commandType(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'command',
            'action' => 'executed',
            'description' => 'Executed command: '.fake()->word(),
            'metadata' => [
                'command' => fake()->word(),
                'exit_code' => 0,
            ],
        ]);
    }

    public function errorType(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'error',
            'action' => 'failed',
            'description' => 'Operation failed',
            'metadata' => [
                'error' => fake()->sentence(),
            ],
        ]);
    }
}
