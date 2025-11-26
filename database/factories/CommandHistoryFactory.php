<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommandHistory>
 */
class CommandHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $commands = [
            'ls -la',
            'pwd',
            'cd /var/www',
            'cat config.php',
            'tail -f /var/log/nginx/access.log',
            'grep "error" /var/log/app.log',
            'df -h',
            'ps aux',
            'top',
            'free -m',
        ];

        return [
            'user_id' => \App\Models\User::factory(),
            'server_id' => \App\Models\Server::factory(),
            'command' => fake()->randomElement($commands),
            'current_directory' => fake()->randomElement(['/home', '/var/www', '/etc', '/tmp', '~']),
            'execution_time' => fake()->randomFloat(3, 0.1, 5.0),
        ];
    }
}
