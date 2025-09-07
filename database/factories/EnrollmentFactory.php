<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $studentIds = User::students()->pluck('id')->toArray();
        $courseIds = Course::pluck('id')->toArray();
        
        return [
            'student_id' => $studentIds ? fake()->randomElement($studentIds) : User::factory()->student(),
            'course_id' => $courseIds ? fake()->randomElement($courseIds) : Course::factory(),
            'price_paid' => fake()->randomFloat(2, 19.99, 299.99),
            'status' => fake()->randomElement(['active', 'completed', 'cancelled']),
            'progress_percentage' => fake()->randomFloat(2, 0, 100),
            'completed_at' => fake()->optional(0.3)->dateTimeBetween('-6 months', 'now'),
            'last_accessed_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Indicate that the enrollment is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'completed_at' => null,
            'progress_percentage' => fake()->randomFloat(2, 0, 95),
        ]);
    }

    /**
     * Indicate that the enrollment is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'progress_percentage' => 100.00,
            'completed_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * Indicate that the enrollment is just started.
     */
    public function justStarted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'progress_percentage' => fake()->randomFloat(2, 0, 15),
            'completed_at' => null,
            'last_accessed_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ]);
    }
}