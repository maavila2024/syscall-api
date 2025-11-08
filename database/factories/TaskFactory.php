<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;
use App\Models\Priority;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taskCode = 1000000 + $this->faker->unique()->numberBetween(1, 999999);
        
        return [
            'segment' => '1',
            'task_type' => '1',
            'task_code' => (string) $taskCode,
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'owner_id' => User::inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'responsible_id' => $this->faker->optional()->randomElement([
                null,
                User::inRandomOrder()->first()?->id ?? User::factory()->create()->id
            ]),
            'task_status_id' => TaskStatus::inRandomOrder()->first()?->id ?? 1,
            'system_screen' => $this->faker->optional()->sentence(),
            'observation' => $this->faker->optional()->paragraph(),
            'priority_id' => Priority::inRandomOrder()->first()?->id ?? 1,
            'priority_justification' => $this->faker->optional()->sentence(),
            'expected_date' => $this->faker->optional()->date(),
            'finish_date' => null,
            'status' => '1',
            'sequence' => 0,
        ];
    }

    /**
     * Indicate that the task is open.
     */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'task_status_id' => 1, // Aberto
            'finish_date' => null,
        ]);
    }

    /**
     * Indicate that the task is in development.
     */
    public function inDevelopment(): static
    {
        return $this->state(fn (array $attributes) => [
            'task_status_id' => 2, // Em desenvolvimento
            'finish_date' => null,
        ]);
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'task_status_id' => 5, // Concluído
            'finish_date' => now()->format('Y-m-d'),
        ]);
    }
}

